﻿<?php
    /**
     * View do Perfil do Aluno
     *
     * @copyright     Copyright 2010-2011, INFOmoz (Informática-Moçambique) (http://infomoz.net)
     * @link          http://infomoz.net/opensga OpenSGA - Sistema de Gestão Académica
     * @author          Elisio Leonardo (elisio.leonardo@gmail.com)
     * @package       opensga
     * @subpackage    opensga.core.estudantes
     * @version       OpenSGA v 0.5.0
     * @since         OpenSGA v 0.1.0
     *
     */
    $this->extend('/Common/Alunos/perfil_estudante');
?>


<?php
    $this->start('breadcumbs');
    $this->BreadCumbs->addCrumb(__('Alunos'), '/alunos');
    $this->BreadCumbs->addCrumb($aluno['Entidade']['name'], '#');
    $this->BreadCumbs->addCrumb(__('Perfil'), '#');
    echo $this->BreadCumbs->css('/assets/plugins/bootstrap-fileupload/bootstrap-fileupload.min.css');
    echo $this->BreadCumbs->css('/assets/plugins/bootstrap-social-buttons/social-buttons-3.css');
    $this->end();
?>

<?php $this->start('main-actions') ?>
    <div class="action-list">
        <div class="row">
            <div class="col-sm-2">
                <?php echo $this->Html->link('<i class="fa fa-user fa-2x"></i>Editar Perfil',
                        ['controller' => 'alunos', 'action' => 'editar_perfil', 'estudante' => true],
                        ['escape' => false, 'class' => 'btn btn-icon btn-block']) ?>

            </div>
            <div class="col-sm-2">
                <?php echo $this->Html->link('<i class="fa fa-print  fa-2x"></i>	Ficha Cadastro<span class="badge badge-danger"> Brevemente </span>',
                        ['controller' => 'alunos', 'action' => 'ficha_cadastro', $aluno['Aluno']['id']],
                        ['target' => 'blank', 'escape' => false, 'class' => 'btn btn-icon btn-block']) ?>

            </div>
            <div class="col-sm-2">
                <?php echo $this->Html->link('<i class="fa fa-print  fa-2x"></i>	Renovar Matricula',
                        ['controller' => 'matriculas', 'action' => 'renovar_matricula', 'estudante' => true],
                        ['escape' => false, 'class' => 'btn btn-icon btn-block']) ?>

            </div>
            <div class="col-sm-2">
                <?php echo $this->Html->link('<i class="fa fa-print  fa-2x"></i>Enviar SMS<span class="badge badge-danger"> Brevemente </span>',
                        ['controller' => 'alunos', 'action' => 'enviar_sms', $aluno['Aluno']['id']],
                        ['escape' => false, 'class' => 'btn btn-icon btn-block ']) ?>

            </div>
        </div>
        <div class="row">

        </div>
    </div>

<?php
    $this->end();
    $this->start('actividades-recentes');
?>
    <ul class="activities">
        <li>
            <a href="javascript:void(0)" class="activity">
                <i class="clip-upload-2 circle-icon circle-green"></i>
                <span class="desc">Nada para mostrar no momento.</span>
                <div class="time">
                    <i class="fa fa-time bigger-110"></i>
                    <!--2 hours ago-->
                </div>
            </a>
        </li>

    </ul>
<?php
    $this->end();
