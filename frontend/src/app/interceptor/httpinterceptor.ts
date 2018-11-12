import { Injectable, Injector } 								from '@angular/core';
import { Router } 												from '@angular/router';
import { HttpEvent, HttpInterceptor, HttpHandler, HttpRequest } from '@angular/common/http';
import { ToastrService } 										from 'ngx-toastr';
import { Observable } 											from 'rxjs/Rx';
import 'rxjs/add/observable/throw'
import 'rxjs/add/operator/catch';

import { RefreshService } 										from '@services/refresh.service';

@Injectable()
export class MyHttpInterceptor implements HttpInterceptor {

	constructor(private inj: Injector,
				private toastr: ToastrService,
				private router: Router) { }

	intercept(req: HttpRequest<any>, next: HttpHandler): Observable<HttpEvent<any>> {



		const Authorization = 'Bearer ' + localStorage.getItem('access_token');

		const authReq = req.clone({ headers: req.headers.set('Authorization', Authorization) });


		return next.handle(authReq).catch((error, caught) => {
			if (error.status === 401) {

				if(error.error.error === 'invalid_credentials') {

					return Observable.throw(error);
				} else {
					let refreshService = this.inj.get(RefreshService);

					return refreshService.refreshToken()
						.flatMap(res => {
						var item = res.json();

						const expiresIn = item.expires_in * 1000 + Date.now();
						localStorage.setItem('expires_in', JSON.stringify(expiresIn));
						localStorage.setItem('access_token', item.access_token);
						localStorage.setItem('refresh_token', item.refresh_token);

						const Authorization = 'Bearer ' + item.access_token;
						const authReq = req.clone({ headers: req.headers.set('Authorization', Authorization) });
						return next.handle(authReq);
					});
				}
			} else if(error.status === 403) {
				// Trying to access api's withbout proper role
				this.toastr.warning('You do not have permission to do that');
				this.router.navigate(['/dashboard']);
				return Observable.throw(error);
			} else if(error.status === 500) {
				// Api not responding
				this.toastr.error('Something went wrong, contact system admin');
				return Observable.throw(error);
			} else {
				return Observable.throw(error);
			}
		}) as any;
	}

}