<?php
    $this->BreadCumbs->addCrumb('Cursos', '/cursos');
    $this->BreadCumbs->addCrumb(h($curso['Curso']['name']), '/cursos/ver_curso/' . $curso['Curso']['id']);
    $this->BreadCumbs->addCrumb('Definir Estado do Curso', '#');


    echo $this->Html->css('/assets/plugins/select2/select2.css');
    echo $this->Html->css('/assets/plugins/datepicker/css/datepicker.css');
?>
<?php $this->extend('/Common/form_basic'); ?>

<?php $this->assign('form-title','Definir Encarregado')?>

<?php $this->start('form-elements') ?>
<?php echo $this->Form->create('Curso', array('role' => 'form', 'enctype' => 'multipart/form-data',
                                                    'class' => '',
                                          'inputDefaults' => array())); ?>
<div class="form-group">
    <label>
        <?php echo __('Novo Estado do Curso') ?>
    </label>
    <?php echo $this->Form->input('estado_objecto_id', array('label' => false, 'div' => false,
                                                       'class' => 'form-control search-select',
                                                       'options'=>$estadoObjectos)); ?>
</div>

<div class="form-group">
    <div class="col-sm-4 col-sm-offset-8">
        <?php echo $this->Form->hidden('curso_id', array('value' => $curso['Curso']['id'])); ?>
        <?php

                echo $this->Form->end(array('label' => __('GRAVAR', true), 'class' => 'btn btn-blue btn-block'));

        ?>

    </div>


</div>
<?php
    $this->end();

?>
<?php $this->start('right-panel')?>
<div class="col-sm-6">
    <!-- start: SELECT BOX PANEL -->
    <div class="panel panel-default">
        <div class="panel-heading">
            <i class="fa fa-external-link-square"></i>
            Detalhes do Curso
            <div class="panel-tools">
                <a href="#" class="btn btn-xs btn-link panel-collapse collapses">
                </a>
                <a data-toggle="modal" href="#panel-config" class="btn btn-xs btn-link panel-config">
                    <i class="fa fa-wrench"></i>
                </a>
                <a href="#" class="btn btn-xs btn-link panel-refresh">
                    <i class="fa fa-refresh"></i>
                </a>
                <a href="#" class="btn btn-xs btn-link panel-expand">
                    <i class="fa fa-resize-full"></i>
                </a>
                <a href="#" class="btn btn-xs btn-link panel-close">
                    <i class="fa fa-times"></i>
                </a>
            </div>
        </div>
        <table class="table table-condensed table-hover">
            <tbody>
            <tr>
                <td>Nome do Curso</td>
                <td><?= $curso['Curso']['name']?></td>
            </tr>
            </tbody>
        </table>

    </div>
    <!-- end: SELECT BOX PANEL -->
</div>
<?php $this->end();?>