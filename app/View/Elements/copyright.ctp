<?php
    /**
     * OpenSGA - Sistema de Gest�o Acad�mica
     *   Copyright (C) 2010-2012  INFOmoz (Inform�tica-Mo�ambique)
     * @copyright     Copyright 2010-2011, INFOmoz (Inform�tica-Mo�ambique) (http://infomoz.net)
     ** @link         http://opensga.com OpenSGA  - Sistema de Gestão Académica
     * @author          Elisio Leonardo (elisio.leonardo@gmail.com)
     * @package       opensga
     * @subpackage    opensga.core.controller
     * @since         OpenSGA v 0.6.0
     * @version       0.6.0
     *
     */
?>

<p>&copy; 2010-<?php echo date('Y') ?> UEM-DRA | Desenvolvido por <strong><?php echo $this->Html->link(__
        (Configure::read
        ('OpenSGA.desenvolvedor')),
                'http://infomoz.net/en/infomoz/sobre-elisio-leonardo/',
                ['title' => 'Sistemas de Gestão para Educação', 'target' => '_blank']) ?></strong>
    <a href='http://demo.opensga.org:8080/job/opensga'>
        <img src='http://demo.opensga.org:8080/buildStatus/icon?job=opensga'>
    </a> 

</p>

<div id="ajudaDialog" style="display: none">
    <h1>Página de Ajuda do OpenSGA</h1>
    <p>Brevemente...</p>
</div>