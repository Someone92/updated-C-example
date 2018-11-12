import { NgModule, APP_INITIALIZER } 			from '@angular/core';
import { HttpModule } 							from '@angular/http';
import { HttpClientModule, HTTP_INTERCEPTORS } 	from '@angular/common/http';
import { BrowserModule } 						from '@angular/platform-browser';
import { RouterModule, PreloadAllModules } 		from '@angular/router';
import { FormsModule, ReactiveFormsModule } 	from '@angular/forms';

import { BrowserAnimationsModule } 				from '@angular/platform-browser/animations';
import { ToastrModule } 						from 'ngx-toastr';


import { LoopObjectModule } 					from './pipe/loop-object.module';

import { AppComponent } 						from './app.component';

import { ROUTES } 								from './app.routes';


import { NgxDatatableModule } 					from '@swimlane/ngx-datatable';


/////////////////
// Interceptor //
/////////////////
import { MyHttpInterceptor } 					from './interceptor/httpinterceptor'

//////////////
// Services //
//////////////
import { MenuService } 							from '@services/menu.service';
import { RefreshService } 						from '@services/refresh.service';
import { AuthService } 							from '@services/auth.service';


////////////
// Guards //
////////////
import { AuthGuard }							from './guards/auth.guard';


////////////
// Layout //
////////////
import { PublicComponent }				 		from '@public/layout';


import { AppConfig }       						from '@config/app.config';

@NgModule({
	declarations: [
		AppComponent,

		// Public
		PublicComponent,

		// Private
	],
	imports: [
		HttpModule,
		LoopObjectModule,
			
		NgxDatatableModule,

		BrowserModule,
		HttpClientModule,

		FormsModule,
		ReactiveFormsModule,

		BrowserAnimationsModule,
		ToastrModule.forRoot(),
		RouterModule.forRoot(ROUTES, { useHash: true, preloadingStrategy: PreloadAllModules })
	],
	providers: [
		AppConfig,
		{ provide: APP_INITIALIZER, 
			useFactory: (config: AppConfig) => () => config.load(), 
			deps: [AppConfig], multi: true 
		},
		{
			provide: HTTP_INTERCEPTORS,
			useClass: MyHttpInterceptor,
			multi: true
		},
		MenuService,
		RefreshService,
		AuthService,
		
		AuthGuard
	],
	bootstrap: [
		AppComponent
	]
})
export class AppModule { }
