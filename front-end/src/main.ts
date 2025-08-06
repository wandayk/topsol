import { bootstrapApplication } from '@angular/platform-browser';
import { AppComponent } from './app/app.component';
import { importProvidersFrom } from '@angular/core';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import { RouterModule } from '@angular/router';
import { StoreModule } from '@ngrx/store';
import { EffectsModule } from '@ngrx/effects';
import { StoreDevtoolsModule } from '@ngrx/store-devtools';
import { environment } from './environments/environment';
import { AuthGuard } from './app/features/auth/auth.guard';
import { AuthInterceptor } from './app/features/auth/auth.interceptor';

bootstrapApplication(AppComponent, {
  providers: [
    importProvidersFrom(
      BrowserAnimationsModule,
      HttpClientModule,
      RouterModule.forRoot([
        { path: '', redirectTo: '/dashboard', pathMatch: 'full' },
        { path: 'login', loadComponent: () => import('./app/features/auth/login/login.component').then(m => m.LoginComponent) },
        { 
          path: 'dashboard', 
          loadComponent: () => import('./app/features/dashboard/dashboard.component').then(m => m.DashboardComponent),
          canActivate: [AuthGuard]
        },
        { 
          path: 'clients', 
          loadComponent: () => import('./app/features/clients/client-list/client-list.component').then(m => m.ClientListComponent),
          canActivate: [AuthGuard]
        },
        { 
          path: 'collections', 
          loadComponent: () => import('./app/features/collections/collection-list/collection-list.component').then(m => m.CollectionListComponent),
          canActivate: [AuthGuard]
        },
        { 
          path: 'notes', 
          loadComponent: () => import('./app/features/notes/note-list/note-list.component').then(m => m.NoteListComponent),
          canActivate: [AuthGuard]
        },
        { 
          path: 'financial', 
          loadComponent: () => import('./app/features/financial/financial.component').then(m => m.FinancialComponent),
          canActivate: [AuthGuard]
        },
        { path: '**', redirectTo: '/dashboard' }
      ]),
      StoreModule.forRoot({}),
      EffectsModule.forRoot([]),
      StoreDevtoolsModule.instrument({
        maxAge: 25,
        logOnly: environment.production
      })
    ),
    {
      provide: HTTP_INTERCEPTORS,
      useClass: AuthInterceptor,
      multi: true
    }
  ]
}).catch(err => console.error(err));