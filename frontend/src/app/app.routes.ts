import { Routes }							from '@angular/router';

// Guard
import { AuthGuard } 						from './guards/auth.guard';

// Layout
import { PublicComponent, PUBLIC_ROUTES }	from '@public/layout';

export const ROUTES: Routes = [
    { path: '', redirectTo: 'dashboard', pathMatch: 'full' },
    { path: '', component: PublicComponent, children: PUBLIC_ROUTES },
    { path: '**', redirectTo: 'dashboard' }
];