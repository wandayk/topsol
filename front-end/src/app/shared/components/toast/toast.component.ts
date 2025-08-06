import { Component, Input, Output, EventEmitter, OnInit, OnDestroy } from '@angular/core';
import { CommonModule } from '@angular/common';

export interface Toast {
  id: string;
  type: 'success' | 'error' | 'warning' | 'info';
  title?: string;
  message: string;
  duration?: number;
  autoClose?: boolean;
}

@Component({
  selector: 'app-toast',
  standalone: true,
  imports: [CommonModule],
  template: `
    <div 
      class="max-w-sm w-full bg-transparent border border-white/5 rounded-sm overflow-hidden shadow-2xl animate-slide-in"
      [attr.data-toast-id]="toast.id"
      (mouseenter)="onMouseEnter()"
      (mouseleave)="onMouseLeave()"
    >
      <!-- Toast Content -->
      <div class="flex items-start p-4">
        <!-- Icon -->
        <div class="flex-shrink-0 mr-3">
          <ng-container [ngSwitch]="toast.type">
            <!-- Success Icon -->
            <svg *ngSwitchCase="'success'" class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            
            <!-- Error Icon -->
            <svg *ngSwitchCase="'error'" class="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            
            <!-- Warning Icon -->
            <svg *ngSwitchCase="'warning'" class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            
            <!-- Info Icon -->
            <svg *ngSwitchCase="'info'" class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
          </ng-container>
        </div>
        
        <!-- Content -->
        <div class="flex-1 min-w-0">
          <p *ngIf="toast.title" class="text-xs tracking-widest font-semibold mb-1" [ngClass]="getTitleClass()">
            {{ toast.title }}
          </p>
          <p class="text-xs text-white/90">
            {{ toast.message }}
          </p>
        </div>
        
        <!-- Close Button -->
        <button 
          (click)="close()"
          class="flex-shrink-0 ml-8 text-white/60 hover:text-white transition-colors duration-200"
        >
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
          </svg>
        </button>
      </div>
      
      <!-- Progress Bar -->
      <div 
        *ngIf="toast.autoClose !== false && showProgressBar"
        class="h-1 bg-white/20"
      >
        <div 
          class="h-full transition-all ease-linear"
          [ngClass]="getProgressBarClass()"
          [style.width.%]="progressWidth"
        ></div>
      </div>
    </div>
  `,
  styles: [`
    @keyframes slide-in {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes slide-out {
      from {
        transform: translateX(0);
        opacity: 1;
      }
      to {
        transform: translateX(100%);
        opacity: 0;
      }
    }

    .animate-slide-in {
      animation: slide-in 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
    }

    .animate-slide-out {
      animation: slide-out 0.3s cubic-bezier(0.55, 0.055, 0.675, 0.19) forwards;
    }
  `]
})
export class ToastComponent implements OnInit, OnDestroy {
  @Input() toast!: Toast;
  @Output() remove = new EventEmitter<string>();
  
  progressWidth = 100;
  showProgressBar = true;
  isClosing = false;
  
  private progressInterval?: any;
  private autoCloseTimeout?: any;

  ngOnInit() {
    if (this.toast.autoClose !== false) {
      this.startProgress();
      this.startAutoClose();
    }
  }

  ngOnDestroy() {
    this.clearTimers();
  }


  getProgressBarClass(): string {
    switch (this.toast.type) {
      case 'success':
        return 'bg-green-400';
      case 'error':
        return 'bg-red-400';
      case 'warning':
        return 'bg-yellow-400';
      case 'info':
        return 'bg-blue-400';
      default:
        return 'bg-white';
    }
  }

  getTitleClass(): string {
    switch (this.toast.type) {
      case 'success':
        return 'text-green-300';
      case 'error':
        return 'text-red-300';
      case 'warning':
        return 'text-yellow-300';
      case 'info':
        return 'text-blue-300';
      default:
        return 'text-white';
    }
  }

  close() {
    if (this.isClosing) return;
    
    this.isClosing = true;
    this.clearTimers();
    
    // Add slide-out animation class
    const element = document.querySelector(`[data-toast-id="${this.toast.id}"]`) as HTMLElement;
    if (element) {
      element.classList.remove('animate-slide-in');
      element.classList.add('animate-slide-out');
    }
    
    // Wait for animation to complete before removing
    setTimeout(() => {
      this.remove.emit(this.toast.id);
    }, 300);
  }

  private startProgress() {
    const duration = this.toast.duration || 5000;
    const interval = 50;
    const decrement = (100 * interval) / duration;

    this.progressInterval = setInterval(() => {
      this.progressWidth -= decrement;
      if (this.progressWidth <= 0) {
        this.progressWidth = 0;
        this.clearTimers();
      }
    }, interval);
  }

  private startAutoClose() {
    const duration = this.toast.duration || 5000;
    this.autoCloseTimeout = setTimeout(() => {
      this.close();
    }, duration);
  }

  private clearTimers() {
    if (this.progressInterval) {
      clearInterval(this.progressInterval);
    }
    if (this.autoCloseTimeout) {
      clearTimeout(this.autoCloseTimeout);
    }
  }

  // Pause/Resume on hover
  onMouseEnter() {
    if (this.progressInterval) {
      clearInterval(this.progressInterval);
    }
    if (this.autoCloseTimeout) {
      clearTimeout(this.autoCloseTimeout);
    }
  }

  onMouseLeave() {
    if (this.toast.autoClose !== false && this.progressWidth > 0) {
      this.startProgress();
      this.startAutoClose();
    }
  }
}
