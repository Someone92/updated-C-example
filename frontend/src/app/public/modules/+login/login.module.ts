import { CommonModule } from '@angular/common';
import { NgModule } from '@angular/core';
import { RouterModule } from '@angular/router';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';


import { routes } from './login.routes';
import { LoginComponent } from './login.component';


@NgModule({
	declarations: [
		LoginComponent,
	],
	imports: [
		CommonModule,
		ReactiveFormsModule,
		RouterModule.forChild(routes),
	]
})
export class LoginModule {
	public static routes = routes;
}