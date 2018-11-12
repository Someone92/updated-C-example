import { Component } 							from '@angular/core';
import { Router }								from '@angular/router';
import { FormBuilder, FormGroup, Validators } 	from '@angular/forms';
import { Title } 								from '@angular/platform-browser';
import { ToastrService } 						from 'ngx-toastr';
import { AuthService } 							from '@services/auth.service';

import { AppConfig } from '@config/app.config';

@Component({
    selector: 'div.login-container',
    templateUrl: 'login.component.html',
    styleUrls: ['./login.component.sass']
})
export class LoginComponent {
	loginForm: FormGroup;
	_item: Object = {};

	constructor(private router: Router,
				private formBuilder: FormBuilder,
				private titleService: Title,
				private toastr: ToastrService,
				private config: AppConfig,
				private authService: AuthService) {
		
		this.logout();
		this.loginForm = formBuilder.group({
			email: ['', [Validators.required, Validators.email]],
			password: ['', [Validators.required, Validators.minLength(6)]],
			remember: ''
		});
		this.titleService.setTitle('Login | Combitech');
	}

	public login() {
		const email = this.loginForm.value.email;
		const password = this.loginForm.value.password;
		
		this._item = {
			username: email,
			password: password,
			client_id: 2,
			client_secret: this.config.getConfig('client_secret'),
			grant_type: 'password'
		}

		this.authService.postLogin(this._item).subscribe(
			response => this.handleResponse(response),
			error => this.handleError(error)
		)
	}
	handleResponse(response: any) {
		const expiresIn = response.expires_in * 1000 + Date.now();
		localStorage.setItem('expires_in', JSON.stringify(expiresIn));
		localStorage.setItem('access_token', response.access_token);
		localStorage.setItem('refresh_token', response.refresh_token);

		let user = JSON.stringify(response.user);
		localStorage.setItem('user', user);

	}
	handleError(error: any) {
		if(error.status === 401) {
			this.toastr.error(error.error.error, 'Email/Password');
		} else {
			console.log(error);
		}
	}

	public logout() {
		localStorage.clear();
	}


}