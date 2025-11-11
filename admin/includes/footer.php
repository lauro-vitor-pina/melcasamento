        <script>
            class LoadingManager {
                constructor() {
                    this.loadingElement = document.getElementById('globalLoading');
                    this.requestCount = 0;
                    this.setupInterceptors();
                }

                show(message = 'Processando...') {
                    this.requestCount++;
                    this.loadingElement.querySelector('.loading-text').textContent = message;
                    this.loadingElement.classList.add('show');
                    document.body.classList.add('loading');

                    // Timeout de segurança (2 segundos)
                    setTimeout(() => {
                        if (this.loadingElement.classList.contains('show')) {
                            this.hide();
                        }
                    }, 2000);
                }

                hide() {
                    this.requestCount = Math.max(0, this.requestCount - 1);
                    if (this.requestCount === 0) {
                        setTimeout(() => {
                            this.loadingElement.classList.remove('show');
                            document.body.classList.remove('loading');
                        }, 300);
                    }
                }

                setupInterceptors() {
                    // Interceptar clicks em links
                    document.addEventListener('click', (e) => {
                        const link = e.target.closest('a');
                        if (link && link.href && !link.href.includes('javascript:') &&
                            !link.hasAttribute('data-bs-toggle') &&
                            !link.hasAttribute('data-bs-target')) {

                            // Não interceptar links externos ou downloads
                            if (link.target === '_blank' ||
                                link.href.includes('mailto:') ||
                                link.href.includes('tel:') ||
                                link.getAttribute('download')) {
                                return;
                            }

                            // Não interceptar links que já tem loading próprio
                            if (link.hasAttribute('data-no-loading')) {
                                return;
                            }

                            e.preventDefault();
                            this.show('Navegando...');

                            setTimeout(() => {
                                window.location.href = link.href;
                            }, 100);
                        }
                    });

                    // Interceptar submits de formulários
                    document.addEventListener('submit', (e) => {
                        const form = e.target;

                        // Não interceptar forms com data-no-loading
                        if (form.hasAttribute('data-no-loading')) {
                            return;
                        }

                        this.show('Enviando dados...');

                        // Se o form não for interceptado pelo JS, o loading será removido na próxima página
                        if (this.loadingElement.classList.contains('show')) {
                            this.hide();
                        }
                    });

                    // Interceptar mudanças em selects/inputs que disparam submit automático
                    document.addEventListener('change', (e) => {
                        const target = e.target;

                        if ((target.tagName === 'SELECT' || target.type === 'checkbox') &&
                            target.hasAttribute('onchange') &&
                            target.getAttribute('onchange').includes('submit')) {

                            this.show('Aplicando filtros...');
                        }
                    });

                    // Interceptar o evento beforeunload (navegação)
                    window.addEventListener('beforeunload', () => {
                        this.show('Carregando...');
                    });

                    // Esconder loading quando a página terminar de carregar
                    window.addEventListener('load', () => {
                        setTimeout(() => {
                            this.hide();
                        }, 500);
                    });
                }
            }

            // Inicializar quando o DOM estiver pronto
            document.addEventListener('DOMContentLoaded', function() {
                const loadingManager = new LoadingManager();

                // Feedback de toque melhorado
                const touchElements = document.querySelectorAll('.touch-feedback');
                touchElements.forEach(el => {
                    el.addEventListener('touchstart', function() {
                        this.style.opacity = '0.7';
                    });

                    el.addEventListener('touchend', function() {
                        this.style.opacity = '1';
                    });
                });

                // Swipe para abrir menu
                let startX = 0;
                let startY = 0;

                document.addEventListener('touchstart', e => {
                    startX = e.touches[0].clientX;
                    startY = e.touches[0].clientY;
                });

                document.addEventListener('touchend', e => {
                    const endX = e.changedTouches[0].clientX;
                    const endY = e.changedTouches[0].clientY;
                    const diffX = endX - startX;
                    const diffY = endY - startY;

                    // Swipe da esquerda para direita (horizontal)
                    if (Math.abs(diffX) > Math.abs(diffY) && diffX > 50 && startX < 50) {
                        const offcanvas = new bootstrap.Offcanvas(document.getElementById('sidebarMobile'));
                        offcanvas.show();
                    }
                });

                // Mostrar hint de swipe uma vez
                if (!localStorage.getItem('swipeHintShown')) {
                    setTimeout(() => {
                        document.getElementById('swipeHint').classList.remove('d-none');
                        setTimeout(() => {
                            document.getElementById('swipeHint').classList.add('d-none');
                            localStorage.setItem('swipeHintShown', 'true');
                        }, 3000);
                    }, 1000);
                }

                // Prevenir zoom em inputs
                document.addEventListener('touchstart', function(e) {
                    if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREEA') {
                        e.target.style.fontSize = '16px';
                    }
                });

                // Detectar dispositivo
                const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
                if (!isMobile) {
                    document.body.style.maxWidth = '768px';
                    document.body.style.margin = '0 auto';
                    document.body.style.borderLeft = '1px solid #dee2e6';
                    document.body.style.borderRight = '1px solid #dee2e6';
                }
            });

            // Função global para mostrar loading manualmente
            function showLoading(message = 'Processando...') {
                const loadingElement = document.getElementById('globalLoading');
                loadingElement.querySelector('.loading-text').textContent = message;
                loadingElement.classList.add('show');
                document.body.classList.add('loading');
            }

            // Função global para esconder loading manualmente
            function hideLoading() {
                const loadingElement = document.getElementById('globalLoading');
                loadingElement.classList.remove('show');
                document.body.classList.remove('loading');
            }
        </script>
        </body>

        </html>