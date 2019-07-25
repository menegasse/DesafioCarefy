<?php if(!class_exists('Rain\Tpl')){exit;}?><!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

<!-- Main content -->
<section class="content">

  <div class="row">
  	<div class="col-md-12">
  		<div class="box box-primary">
            
            <div class="box-header">
              <a href="/pacientes/create" class="btn btn-success"style="background-color:#ff8a73!important;">Cadastrar Paciente</a>
            </div>

            <div class="box-body no-padding">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th style="width: 10px">#</th>
                    <th>Nome</th>
                    <th>Hospital</th>
                    <th>Ativo</th>
                    <th style="width: 140px">&nbsp;</th>
                  </tr>
                </thead>
                <tbody>
                  <!--constroi a tabela de pacientes que foi retornada do banco -->
                  <?php $counter1=-1;  if( isset($patients) && ( is_array($patients) || $patients instanceof Traversable ) && sizeof($patients) ) foreach( $patients as $key1 => $value1 ){ $counter1++; ?>
                  <tr>
                    <td></td>
                    <td><?php echo htmlspecialchars( $value1["name"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td>
                    <td><?php echo htmlspecialchars( $value1["hospital"], ENT_COMPAT, 'UTF-8', FALSE ); ?></td/>
                    <td><?php if( $value1["enabled"] == 1 ){ ?>Sim<?php }else{ ?>Não<?php } ?></td>
                    <td>
                      <a href="/pacientes/edite/<?php echo htmlspecialchars( $value1["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i> Editar</a>
                      <a href="/pacientes/<?php echo htmlspecialchars( $value1["id"], ENT_COMPAT, 'UTF-8', FALSE ); ?>/delete" onclick="return confirm('Deseja realmente excluir este registro?')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Excluir</a>
                    </td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
  	</div>
  </div>

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->