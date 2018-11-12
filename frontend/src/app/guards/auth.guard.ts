import { Injectable } from '@angular/core';
import { Router, CanActivate, CanActivateChild, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';
import { Observable } from 'rxjs/Observable';
import { AuthService } from '@services/auth.service';

@Injectable()
export class AuthGuard implements CanActivate, CanActivateChild {
	
	constructor(private authService: AuthService,
				private router: Router) { }

	canActivate(
		next: ActivatedRouteSnapshot,
		state: RouterStateSnapshot): Observable<boolean> | Promise<boolean> | boolean {
		if(!this.authService.authenticated) {
			this.router.navigate(['/login']);
			return false;
		}
		return true;
	}

	canActivateChild() {
		return true;
	}
}