<?php

ini_set('memory_limit', "2048M");
ini_set('xdebug.max_nesting_level', 20000);
App::uses('AuditableConfig', 'Auditable.Lib');
App::uses('OpenSGAGoogle', 'Lib');

class S3Shell extends AppShell
{

    public $uses = [
        'Inscricao',
        'Turma',
        'Matricula',
        'Curso',
        'UnidadeOrganica',
        'Candidatura',
        'Aluno',
        'EstadoAluno',
        'PlanoEstudo',
        'Disciplina',
        'DisciplinaPlanoEstudo',
        'HistoricoCurso',
        'AnoLectivo',
        'AlumniCandidatoAlumni',
        'Requisicoes.RequisicoesPedido',
        'Entidade',
        'User',
        'SmsNotification',
    ];

    public function test()
    {
        $google = new OpenSGAGoogle();
        try {

            $emailCriado = $google->createUser('teste.email.2@uem.ac.mz', 'Teste','Email','siga@12345678');
            debug($emailCriado);
        } catch (Exception $e) {
            debug($e->getMessage());
            debug($e->getCode());
        }
    }


}
