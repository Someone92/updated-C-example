import { Routes } 				from '@angular/router';

export const PUBLIC_ROUTES: Routes = [
    { path: '', redirectTo: 'login', pathMatch: 'full' },
    { path: 'login', loadChildren: 'app/public/modules/+login#LoginModule' },
    { path: 'register', loadChildren: 'app/public/modules/+register#RegisterModule' }
];