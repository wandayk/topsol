// Exemplo de como testar os toasts em qualquer componente

// 1. No constructor, injete o ToastService:
constructor(private toastService: ToastService) {}

// 2. Métodos para testar diferentes tipos de toast:

testSuccessToast() {
  this.toastService.success('Login realizado com sucesso!', 'Bem-vindo');
}

testErrorToast() {
  this.toastService.error('Credenciais inválidas', 'Erro de Login');
}

testWarningToast() {
  this.toastService.warning('Atenção! Verifique os dados inseridos.', 'Aviso');
}

testInfoToast() {
  this.toastService.info('Nova atualização disponível.', 'Informação');
}

// 3. Para testar no template, adicione botões temporários:
/*
<button (click)="testSuccessToast()" class="p-2 bg-green-500 text-white rounded">Sucesso</button>
<button (click)="testErrorToast()" class="p-2 bg-red-500 text-white rounded">Erro</button>
<button (click)="testWarningToast()" class="p-2 bg-yellow-500 text-white rounded">Aviso</button>
<button (click)="testInfoToast()" class="p-2 bg-blue-500 text-white rounded">Info</button>
*/

// 4. Os toasts agora têm:
// ✨ Animação suave de entrada (direita para esquerda)
// ✨ Animação de saída
// ✨ Bordas suaves com efeito glass
// ✨ Gradiente sutil nas bordas
// ✨ Efeito hover
// ✨ Auto-close configurável
// ✨ Pause no hover
// ✨ Barra de progresso colorida
