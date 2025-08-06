import { Injectable } from '@angular/core';
import { BehaviorSubject } from 'rxjs';
import { Toast } from '../components/toast/toast.component';

@Injectable({
  providedIn: 'root'
})
export class ToastService {
  private toastsSubject = new BehaviorSubject<Toast[]>([]);
  public toasts$ = this.toastsSubject.asObservable();

  private generateId(): string {
    return Math.random().toString(36).substr(2, 9);
  }

  private addToast(toast: Omit<Toast, 'id'>): void {
    const newToast: Toast = {
      ...toast,
      id: this.generateId(),
      duration: toast.duration || 5000,
      autoClose: toast.autoClose !== false
    };

    const currentToasts = this.toastsSubject.value;
    this.toastsSubject.next([...currentToasts, newToast]);
  }

  success(message: string, title?: string, duration?: number): void {
    this.addToast({
      type: 'success',
      message,
      title,
      duration
    });
  }

  error(message: string, title?: string, duration?: number): void {
    this.addToast({
      type: 'error',
      message,
      title,
      duration: duration || 7000 // Error messages stay longer by default
    });
  }

  warning(message: string, title?: string, duration?: number): void {
    this.addToast({
      type: 'warning',
      message,
      title,
      duration
    });
  }

  info(message: string, title?: string, duration?: number): void {
    this.addToast({
      type: 'info',
      message,
      title,
      duration
    });
  }

  remove(id: string): void {
    const currentToasts = this.toastsSubject.value;
    const filteredToasts = currentToasts.filter(toast => toast.id !== id);
    this.toastsSubject.next(filteredToasts);
  }

  clear(): void {
    this.toastsSubject.next([]);
  }
}
