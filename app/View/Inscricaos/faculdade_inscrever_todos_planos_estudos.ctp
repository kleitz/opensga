
<?php
    if (isset($planoEstudoId)) {
        $formUrl = ['controller' => 'inscricaos', 'action' => 'inscrever', $aluno_id, $matricula_id, $planoEstudoId];
    } else {
        $formUrl = ['controller' => 'inscricaos', 'action' => 'inscrever', $aluno_id, $matricula_id];
    }
?>
<?php echo $this->Form->create('Inscricao', [
        'url'           => $formUrl,
        'role'          => 'form',
        'id'            => 'validate-form',
        'inputDefaults' => [
                'div'       => 'form-group',
                'wrapInput' => false,
                'class'     => 'form-control',
        ],
]); ?>
    <div class="row">

        <?php
            $anoActual = 0;
            $semestreActual = 0;
            foreach ($disciplinas as $disciplina) {

                $anoAnterior = $anoActual;
                $semestreAnterior = $semestreActual;
                $anoActual = $disciplina['DisciplinaPlanoEstudo']['ano_curricular'];
                $semestreActual = $disciplina['DisciplinaPlanoEstudo']['semestre_curricular'];

                if ($anoAnterior != $anoActual || $semestreAnterior != $semestreActual) {
                    if ($anoAnterior != 0) {
                        echo "</fieldset></div>";
                    }
                    if ($semestreAnterior % 2 == 0 && $anoAnterior % 2 == 0) {
                        echo '</div><div class="row">';
                    }
                    echo '<div class="col-sm-3"><fieldset><legend> Ano Curricular ' . $disciplina['DisciplinaPlanoEstudo']['ano_curricular'] . ", Semestre Curricular" . $disciplina['DisciplinaPlanoEstudo']['semestre_curricular'] . "</legend>";
                }
                ?>
                <?php echo $this->Form->input('disciplinas.' . $disciplina['DisciplinaPlanoEstudo']['id'], [
                        'label' => $disciplina['Disciplina']['name'] . "(" . $disciplina['DisciplinaPlanoEstudo']['ano_curricular'] . "," . $disciplina['DisciplinaPlanoEstudo']['semestre_curricular'] . ")",
                        'class' => false,
                        'type'  => 'checkbox',
                        'value' => $disciplina['DisciplinaPlanoEstudo']['id'],
                ]); ?>
                <?php

            }
            echo "</fieldset></div>";
        ?>
    </div>
    <div class="clear"></div>
    <div class="row">

        <?php echo $this->Form->input('aluno_id',
                ['type' => 'hidden', 'value' => $aluno_id, 'label' => false, 'div' => false]); ?>
        <?php echo $this->Form->input('matricula_id',
                ['type' => 'hidden', 'value' => $matricula_id, 'label' => false, 'div' => false]); ?>

        <div class="form-group">
            <div class="col-sm-2 col-sm-offset-8">
                <?php echo $this->Form->end([
                        'class' => 'btn btn-blue next-step btn-block',
                        'label' => __('Inscrever Aluno'),
                ]); ?></li>
            </div>
        </div>

    </div>

<?php $this->Html->scriptStart(['inline' => false, 'block' => 'scriptBottom']); ?>
    $(document).ready(function() {
    $('input').iCheck({
    checkboxClass: 'icheckbox_minimal-green',
    radioClass: 'iradio_minimal',
    increaseArea: '20%' // optional
    });
    });
<?php $this->Html->scriptEnd(); ?>