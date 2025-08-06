import { Component, OnInit, OnDestroy } from '@angular/core';
import { CommonModule } from '@angular/common';
import { Subscription } from 'rxjs';
import { ToastService } from '../../services/toast.service';
import { ToastComponent, Toast } from '../toast/toast.component';

@Component({
  selector: 'app-toast-container',
  standalone: true,
  imports: [CommonModule, ToastComponent],
  template: `
    <div class="fixed top-4 right-4 z-[9999] space-y-2">
      <app-toast
        *ngFor="let toast of toasts; trackBy: trackByFn"
        [toast]="toast"
        (remove)="removeToast($event)"
      ></app-toast>
    </div>
  `
})
export class ToastContainerComponent implements OnInit, OnDestroy {
  toasts: Toast[] = [];
  private subscription?: Subscription;

  constructor(private toastService: ToastService) {}

  ngOnInit() {
    this.subscription = this.toastService.toasts$.subscribe(toasts => {
      this.toasts = toasts;
    });
  }

  ngOnDestroy() {
    if (this.subscription) {
      this.subscription.unsubscribe();
    }
  }

  removeToast(id: string) {
    this.toastService.remove(id);
  }

  trackByFn(index: number, toast: Toast): string {
    return toast.id;
  }
}
