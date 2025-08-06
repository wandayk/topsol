import { bootstrapApplication } from '@angular/platform-browser';
import { AppComponent } from './app/app.component';
import { importProvidersFrom } from '@angular/core';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { HttpClientModule } from '@angular/common/http';
import { RouterModule } from '@angular/router';
import { MatSnackBarModule } from '@angular/material/snack-bar';
import { StoreModule } from '@ngrx/store';
import { EffectsModule } from '@ngrx/effects';
import { StoreDevtoolsModule } from '@ngrx/store-devtools';
import { environment } from './environments/environment';

bootstrapApplication(AppComponent, {
  providers: [
    importProvidersFrom(
      BrowserAnimationsModule,
      HttpClientModule,
      MatSnackBarModule,
      RouterModule.forRoot([
        { path: '', redirectTo: '/dashboard', pathMatch: 'full' },
        { path: 'login', loadComponent: () => import('./app/features/auth/login/login.component').then(m => m.LoginComponent) },
        { path: 'dashboard', loadComponent: () => import('./app/features/dashboard/dashboard.component').then(m => m.DashboardComponent) },
        { path: 'clients', loadComponent: () => import('./app/features/clients/client-list/client-list.component').then(m => m.ClientListComponent) },
        { path: 'collections', loadComponent: () => import('./app/features/collections/collection-list/collection-list.component').then(m => m.CollectionListComponent) },
        { path: 'notes', loadComponent: () => import('./app/features/notes/note-list/note-list.component').then(m => m.NoteListComponent) },
        { path: 'financial', loadComponent: () => import('./app/features/financial/financial.component').then(m => m.FinancialComponent) },
        { path: '**', redirectTo: '/dashboard' }
      ]),
      StoreModule.forRoot({}),
      EffectsModule.forRoot([]),
      StoreDevtoolsModule.instrument({
        maxAge: 25,
        logOnly: environment.production
      })
    )
  ]
}).catch(err => console.error(err));