<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Convidados</title>

    <link rel="icon" type="image/svg+xml" href="assets/icons/favicon.svg">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
        :root {
            --primary-color: #667eea;
            --secondary-color: #764ba2;
        }

        body {
            font-size: 14px;
        }

        /* === MOBILE FIRST === */

        /* Sidebar Mobile */
        .offcanvas {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }

        .offcanvas .nav-link {
            color: #fff;
            padding: 12px 15px;
            margin: 2px 0;
            border-radius: 8px;
            font-size: 15px;
        }

        .offcanvas .nav-link:hover,
        .offcanvas .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
        }

        .offcanvas .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Top Bar Mobile */
        .top-bar {
            background: white;
            border-bottom: 1px solid #dee2e6;
            padding: 10px 0;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .menu-toggle {
            border: none;
            background: none;
            font-size: 1.5rem;
            color: var(--primary-color);
        }

        /* === DESKTOP === */
        @media (min-width: 992px) {
            body {
                font-size: 15px;
            }

            /* Esconde elementos mobile no desktop */
            .top-bar,
            .offcanvas {
                display: none !important;
            }

            /* Sidebar Desktop */
            .sidebar-desktop {
                display: block !important;
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 280px;
                background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
                z-index: 1000;
                overflow-y: auto;
            }

            .sidebar-desktop .nav-link {
                color: #fff !important;
                padding: 14px 20px;
                margin: 4px 15px;
                border-radius: 8px;
                font-size: 15px;
                transition: all 0.3s ease;
            }

            .sidebar-desktop .nav-link:hover {
                background: rgba(255, 255, 255, 0.15);
                transform: translateX(5px);
                color: #fff !important;
            }

            .sidebar-desktop .nav-link.active {
                background: rgba(255, 255, 255, 0.2);
                font-weight: 600;
                color: #fff !important;
            }

            .sidebar-desktop .nav-link i {
                margin-right: 12px;
                width: 20px;
                text-align: center;
                color: #fff;
            }

            /* Ajusta o conteúdo principal para o desktop */
            .main-content-desktop {
                margin-left: 280px;
                min-height: 100vh;
                background-color: #f8f9fa;
            }

            /* Top Bar Desktop */
            .top-bar-desktop {
                display: block !important;
                background: white;
                border-bottom: 1px solid #dee2e6;
                padding: 15px 30px;
                margin-left: 280px;
            }

            /* Ajusta o grid para desktop */
            .mobile-grid {
                grid-template-columns: 1fr 1fr 1fr 1fr;
                gap: 20px;
            }

            /* Cards maiores no desktop */
            .stat-card {
                margin-bottom: 20px;
            }

            .welcome-card {
                padding: 25px;
            }
        }

        /* === TABLET (768px - 991px) === */
        @media (min-width: 768px) and (max-width: 991px) {
            .mobile-grid {
                grid-template-columns: 1fr 1fr 1fr 1fr;
                gap: 15px;
            }

            .btn-mobile {
                padding: 10px 15px;
                font-size: 14px;
            }
        }

        /* === ESTILOS GLOBAIS === */

        .stat-card {
            border: none;
            border-radius: 12px;
            margin-bottom: 15px;
            transition: transform 0.2s;
        }

        .stat-card:active {
            transform: scale(0.98);
        }

        .welcome-card {
            border: none;
            border-radius: 15px;
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }

        .mobile-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }

        .btn-mobile {
            padding: 12px;
            font-size: 14px;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .touch-improve {
            padding: 15px;
        }

        .list-group-item {
            padding: 12px 15px;
        }
    </style>
</head>

<body>
    <!-- Top Bar Mobile -->
    <div class="top-bar">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col">
                    <button class="menu-toggle" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMobile">
                        <i class="bi bi-list"></i>
                    </button>
                </div>
                <div class="col text-center">
                    <h5 class="mb-0 text-primary">
                        <i class="bi bi-heart-fill"></i>
                        Sistema
                    </h5>
                </div>
                <div class="col text-end">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Perfil</a></li>
                            <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Configurações</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Bar Desktop -->
    <div class="top-bar-desktop" style="display: none;">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0 text-dark">Dashboard</h4>
                <small class="text-muted"><?php echo date('d/m/Y - H:i'); ?></small>
            </div>
            <div class="d-flex align-items-center">
                <span class="text-muted me-3">
                    <i class="bi bi-calendar-check me-2"></i>
                    <?php echo date('d/m/Y H:i'); ?>
                </span>
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle me-2"></i>
                        Administrador
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Meu Perfil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Configurações</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>