<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MEL - <?php if ($page_title != null) {
                        echo $page_title;
                    } ?></title>

    <!-- Favicon Mobile -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23667eea'><path d='M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z'/></svg>">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- CSS 100% Mobile -->
    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
        }

        * {
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-size: 15px;
            background-color: #f8f9fa;
            padding-bottom: 80px;
            /* Espaço para botão flutuante */
        }

        /* === TOP BAR MOBILE === */
        .top-bar {
            background: white;
            border-bottom: 1px solid #dee2e6;
            padding: 12px 0;
            position: sticky;
            top: 0;
            z-index: 1030;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .menu-toggle {
            border: none;
            background: none;
            font-size: 1.6rem;
            color: var(--primary-color);
            padding: 5px;
            border-radius: 8px;
        }

        .menu-toggle:active {
            background-color: rgba(102, 126, 234, 0.1);
        }

        /* === SIDEBAR MOBILE === */
        .offcanvas {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .offcanvas .nav-link {
            color: #fff;
            padding: 16px 20px;
            margin: 3px 0;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 500;
        }

        .offcanvas .nav-link:active {
            background: rgba(255, 255, 255, 0.2);
            transform: scale(0.98);
        }

        .offcanvas .nav-link.active {
            background: rgba(255, 255, 255, 0.25);
            font-weight: 600;
        }

        .offcanvas .nav-link i {
            margin-right: 12px;
            width: 24px;
            text-align: center;
            font-size: 1.2rem;
        }

        /* === CARDS MOBILE === */
        .stat-card {
            border: none;
            border-radius: 16px;
            margin-bottom: 12px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .stat-card:active {
            transform: scale(0.96);
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        }

        .welcome-card {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        /* === GRID MOBILE === */
        .mobile-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }

        /* Tablet: 4 colunas */
        @media (min-width: 768px) {
            .mobile-grid {
                grid-template-columns: 1fr 1fr 1fr 1fr;
                gap: 16px;
            }

            .stat-card {
                margin-bottom: 16px;
            }
        }

        /* === BOTÕES MOBILE === */
        .btn-mobile {
            padding: 14px 16px;
            font-size: 15px;
            border-radius: 12px;
            margin-bottom: 8px;
            font-weight: 500;
            border: 2px solid transparent;
        }

        .btn-mobile:active {
            transform: scale(0.98);
        }

        /* === MELHORIAS DE TOQUE === */
        .touch-area {
            padding: 16px;
        }

        .list-group-item {
            padding: 16px 20px;
            border-left: none;
            border-right: none;
        }

        .list-group-item:first-child {
            border-top: none;
        }

        .list-group-item:last-child {
            border-bottom: none;
        }

        /* === BOTÃO FLUTUANTE === */
        .fab {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            box-shadow: 0 4px 20px rgba(102, 126, 234, 0.4);
            font-size: 1.5rem;
            z-index: 1020;
            transition: all 0.3s ease;
        }

        .fab:active {
            transform: scale(0.9);
            box-shadow: 0 2px 10px rgba(102, 126, 234, 0.6);
        }

        /* === SWIPE INDICATOR === */
        .swipe-hint {
            position: fixed;
            top: 50%;
            left: 10px;
            background: rgba(102, 126, 234, 0.9);
            color: white;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 12px;
            z-index: 1010;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 0.7;
            }

            50% {
                opacity: 1;
            }
        }

        /* === LOADING STATES === */
        .touch-feedback:active {
            opacity: 0.7;
        }

        /* === LOADING OVERLAY === */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(5px);
            display: none;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            z-index: 9999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .loading-overlay.show {
            display: flex;
            opacity: 1;
        }

        .loading-spinner {
            text-align: center;
            background: white;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: 1px solid #e9ecef;
        }

        .loading-text {
            font-size: 16px;
            font-weight: 500;
            color: #667eea;
            margin: 0;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: 3px;
        }

        /* Efeito de pulso para o spinner */
        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .loading-spinner .spinner-border {
            animation: pulse 1.5s ease-in-out infinite;
        }

        /* Desabilitar interação durante loading */
        body.loading {
            pointer-events: none;
        }

        body.loading * {
            cursor: wait !important;
        }
    </style>
</head>

<body>
    
  <!-- Loading Overlay Global -->
    <div id="globalLoading" class="loading-overlay">
        <div class="loading-spinner">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Carregando...</span>
            </div>
            <p class="loading-text mt-3">Processando...</p>
        </div>
    </div>

    <!-- Swipe Hint (aparece só no início) -->
    <div class="swipe-hint d-none" id="swipeHint">
        <i class="bi bi-arrow-right"></i> Arraste para o menu
    </div>

    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-3">
                    <button class="menu-toggle touch-feedback" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile">
                        <i class="bi bi-list"></i>
                    </button>
                </div>
                <div class="col-6 text-center">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-heart-fill me-2"></i>
                        MEL Casamento
                    </h5>
                </div>
                <div class="col-3 text-end">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle touch-feedback" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item touch-feedback" href="#"><i class="bi bi-person me-2"></i>Meu Perfil</a></li>
                            <li><a class="dropdown-item touch-feedback" href="#"><i class="bi bi-gear me-2"></i>Configurações</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger touch-feedback" href="#"><i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>