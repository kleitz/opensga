<?php
    /**
     * OpenSGA - Sistema de Gest�o Acad�mica
     *   Copyright (C) 2010-2011  INFOmoz (Inform�tica-Mo�ambique)
     *
     * Este programa � um software livre: Voc� pode redistribuir e/ou modificar
     * todo ou parte deste programa, desde que siga os termos da licen�a por nele
     * estabelecidos. Grande parte do c�digo deste programa est� sob a licen�a
     * GNU Affero General Public License publicada pela Free Software Foundation.
     * A vers�o original desta licen�a est� dispon�vel na pasta raiz deste software.
     *
     * Este software � distribuido sob a perspectiva de que possa ser �til para
     * satisfazer as necessidades dos seus utilizadores, mas SEM NENHUMA GARANTIA. Veja
     * os termos da licen�a GNU Affero General Public License para mais detalhes
     *
     * As redistribui��es deste software, mesmo quando o c�digo-fonte for modificado significativamente,
     * devem manter est� informa��o legal, assim como a licen�a original do software.
     *
     * @copyright     Copyright 2010-2011, INFOmoz (Inform�tica-Mo�ambique) (http://infomoz.net)
     ** @link          http://opensga.com OpenSGA  - Sistema de Gestão Académica
     * @author          Elisio Leonardo (elisio.leonardo@gmail.com)
     * @package       opensga
     * @subpackage    opensga.core.controller
     * @since         OpenSGA v 0.10.0.0
     *
     */
?>


<?php //include('menu.ctp'); ?>

<div class="projectos form" id="center-column">
    <div class="top-bar">
        <?php echo $this->Html->link(sprintf(__('Lista de Cargos', true)), ['action' => 'index'],
                ['class' => 'button']); ?>
        <h1><?php echo __('Cargo - visualizar'); ?></h1>

    </div>
    <div class="table">

        <?php
            //O
            echo $this->Form->create('Cargo'); ?>
        <table class="listingform" cellpadding="0" cellspacing="0">
            <th class="full" colspan="2">Dados do Cargo</th>
            <?php
                echo $this->Form->input('name', [
                        'disabled' => 'true',
                        'label'    => 'Nome',
                        'before'   => '<tr><td>',
                        'between'  => '</td><td>',
                        'after'    => '</td></tr>',
                        'div'      => false,
                ]);
            ?>

        </table>
    </div>
</div>