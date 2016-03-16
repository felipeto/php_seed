<?PHP
global $user;
?>
<!DOCTYPE html>
<html dir="ltr" lang="en-US">
    <head>

        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />

        <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
        <?PHP
        Assets::get_custom_styles($this->slug, $this->request_array);
        ?>

        <link rel="stylesheet" href="css/responsive.css" type="text/css" />
        <!--[if lt IE 9]>
                <script src="http://css3-mediaqueries-js.googlecode.com/svn/trunk/css3-mediaqueries.js"></script>
        <![endif]-->

        <?PHP
        Assets::get_custom_scripts($this->slug,$this->request_array);
        ?>

        
        <title>EZ Design</title>

    </head>

    <body class="stretched no-transition">

        <div id="wrapper" class="clearfix">

            <header id="header" class="transparent-header full-header" data-sticky-class="not-dark">

                <div id="header-wrap">

                    <div class="container clearfix">

                        <div id="primary-menu-trigger"><i class="icon-reorder"></i></div>

                        <div id="logo">
                            EZ Design
                        </div>

                        <nav id="primary-menu" class="dark">

                            <ul>
                                <li class="<?=$slug == 'home' ? 'current' : ''?>"><a href="/"><div>Home</div></a>
                                </li>
                                <li class="<?=$slug == 'nosotros' ? 'current' : ''?>"><a href="/nosotros"><div>Nosotros</div></a>
                                </li>
                                <li class="<?=$slug == 'como-funciona' ? 'current' : ''?>"><a href="/como-funciona"><div>Como Funciona</div></a>
                                </li>
                                <li class="<?=$slug == 'contactenos' ? 'current' : ''?>"><a href="/contactenos"><div>Contactenos</div></a>
                                </li>
                                <?PHP
                                if($user->user_id == 0)
                                {
                                ?>
                                <li><a href="#"><div>Disenadores</div></a>
                                    <ul>
                                        <li><a href="#" data-toggle="modal" data-target="#registro-disenador"><div>Registro</div></a>
                                        </li>
                                        <li><a href="#" data-toggle="modal" data-target="#login-disenador"><div>Log in</div></a>
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="#"><div>Proyectos/Disenos</div></a>
                                    <ul>
                                        <li><a href="#" data-toggle="modal" data-target="#registro-proyecto"><div>Registro</div></a></li>
                                        <li><a href="#" data-toggle="modal" data-target="#login-proyecto"><div>Log in</div></a>
                                        </li>
                                    </ul>
                                </li>
                                <?PHP
                                }
                                else
                                {
                                ?>
                                <li><a href="/mi-cuenta"><div>Mi Cuenta</div></a>
                                    <ul>
                                        <li><a href="/log-out"><div>Logout</div></a>
                                        </li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="#"><div>Proyectos/Disenos</div></a>
                                    <ul>
                                        <li>
                                            <a href="/nuevo-proyecto">Crear Proyecto</a>
                                        </li>
                                        <?PHP
                                        if($user->tipo == 'cliente')
                                        {
                                        ?>
                                            <li>
                                                <a href="/mi-cuenta">Mis Proyectos</a>
                                            </li>
                                        <?PHP
                                        }
                                        ?>
                                    </ul>
                                </li>
                                <?PHP
                                }
                                ?>
                            </ul>
                        </nav><!-- #primary-menu end -->

                    </div>

                </div>

            </header><!-- #header end -->