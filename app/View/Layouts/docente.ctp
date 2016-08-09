<?php
    $this->extend('/Common/Layouts/default');
?>
<?php $this->start('main-navigation') ?>
                <!-- start: MAIN NAVIGATION MENU -->
                <ul class="main-navigation-menu">
                    <li class="active open">
                    <li><?php echo $this->Html->link('<i class="clip-home-3"></i>
                        <span class="title"> Pagina Inicial </span><span class="selected"></span>',
                                ['controller' => 'pages', 'action' => 'home', 'docente' => true],
                                ['escape' => false]) ?></li>
                    </li>
                    <li>
                        <a href="javascript:void(0)"><i class="clip-pencil"></i>
                            <span class="title"> Estudantes </span><i class="icon-arrow"></i>
                            <span class="selected"></span>
                        </a>
                        <ul class="sub-menu">
                            <li><?php echo $this->Html->link(__('Meus Estudantes'),
                                        ['controller' => 'alunos', 'action' => 'index', 'docente' => true]) ?></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0)"><i class="clip-user-2"></i>
                            <span class="title">Turmas</span><i class="icon-arrow"></i>
                            <span class="selected"></span>
                        </a>
                        <ul class="sub-menu">

                            <li><?php echo $this->Html->link(__('Minhas Turmas'),
                                        ['controller' => 'turmas', 'action' => 'index', 'docente' => true]) ?></li>

                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0)"><i class="clip-file"></i>
                            <span class="title">Avaliacoes</span><i class="icon-arrow"></i>
                            <span class="selected"></span>
                        </a>
                        <ul class="sub-menu">
                            <li><?php echo $this->Html->link(__('Minhas Avaliacoes'),
                                        ['controller' => 'avaliacaos', 'action' => 'index', 'docente' => true]) ?></li>
                        </ul>
                    </li>
                    <!--<li>
                        <a href="javascript:void(0)"><i class="clip-attachment-2"></i>
                            <span class="title">Avaliacao de Desempenho</span><i class="icon-arrow"></i>
                            <span class="selected"></span>
                        </a>
                        <ul class="sub-menu">
                            <li><?php echo $this->Html->link('Visão Geral',
                                        ['controller' => 'sade_avaliacaos', 'action' => 'index']) ?></li>
                            <li><?php echo $this->Html->link('Auto-Avaliação',
                                        ['controller' => 'sade_auto_avaliacaos', 'action' => 'docente']) ?></li>
                            <li><?php echo $this->Html->link(sprintf(__('Avaliação pelo Estudante', true)),
                                        ['controller' => 'sade_avaliacao_alunos', 'action' => 'index'],
                                        ['class' => 'link']); ?></li>
                            <li><?php echo $this->Html->link(sprintf(__('Avaliação Qualitativa', true)),
                                        ['controller' => 'sade_avaliacao_qualitativas', 'action' => 'index'],
                                        ['class' => 'link']); ?></li>
                            <li><?php echo $this->Html->link(sprintf(__('Relatórios', true)),
                                        ['controller' => 'sade_avaliacaos', 'action' => 'relatorios'],
                                        ['class' => 'link']); ?></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;" class="active">
                            <i class="clip-folder"></i>
                            <span class="title"> Assistentes </span>
                            <i class="icon-arrow"></i>
                        </a>
                        <ul class="sub-menu">
                            <li><?php echo $this->Html->link(__('Meus Assistentes'), [
                                        'controller' => 'docentes',
                                        'action'     => 'ver_assistentes',
                                        'docente'    => true,
                                ]) ?></li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;"><i class="clip-location"></i>
                            <span class="title">Relatorios</span></span><i class="icon-arrow"></i>
                            <span class="selected"></span>
                        </a>
                        <ul class="sub-menu">
                            <li><?php echo $this->Html->link(__('Relatórios Gerais'),
                                        ['controller' => 'relatorios', 'action' => 'index', 'plugin' => false]) ?></li>
                            <li><?php echo $this->Html->link(__('Resumo Semestral'), [
                                        'controller' => 'relatorios',
                                        'action'     => 'resumo_semestral',
                                        'plugin'     => false,
                                ]) ?></li>
                            <li><?php echo $this->Html->link(__('Requisicoes'), [
                                        'controller' => 'relatorios',
                                        'action'     => 'requisicoes',
                                        'plugin'     => false,
                                ]) ?></li>
                            <li><?php echo $this->Html->link(__('Estudantes por Curso'),
                                        ['controller' => 'relatorios', 'action' => 'estudantes_por_curso']) ?></li>
                            <li><?php echo $this->Html->link(sprintf(__('Estudantes por Faculdade', true)),
                                        ['controller' => 'relatorios', 'action' => 'estudantes_por_faculdade'],
                                        ['class' => 'link']); ?></li>
                        </ul>
                    </li>-->
                </ul>
                <!-- end: MAIN NAVIGATION MENU -->
<?php $this->end(); ?>

<?php echo $this->fetch('content');?>