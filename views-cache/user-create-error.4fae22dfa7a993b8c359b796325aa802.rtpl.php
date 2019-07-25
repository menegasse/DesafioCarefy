<?php if(!class_exists('Rain\Tpl')){exit;}?><!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->

<!-- Main content -->
<section class="content">

  <div class="row">
  	<div class="col-md-12">
  		<div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Novo Usuário</h3>
        </div>
        <!-- /.box-header -->
        <!-- form start -->
        <form role="form" action="/user/create/" method="post">
          <div class="box-body">
              <p class="login-box-msg " style="color:red">Login ou Senha inválido.</p>
            <div class="form-group">
              <label for="deslogin">Login</label>
              <input type="text" class="form-control" id="name" name="name" placeholder="Digite o login">
            </div>
            <div class="form-group">
              <label for="desemail">E-mail</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Digite o e-mail">
            </div>
            <div class="form-group">
              <label for="despassword">Senha</label>
              <input type="password" class="form-control" id="password" name="password" placeholder="Digite a senha">
            </div>
            <div class="form-group">
              <label for="despassword">Confirme a Senha</label>
              <input type="password" class="form-control" id="confpassword" name="confpassword" placeholder="Digite novamente  a senha">
            </div>
          </div>
          <!-- /.box-body -->
          <div class="box-footer">
            <button type="submit" class="btn btn-success">Cadastrar</button>

            &emsp;
            <a href="/" class="btn btn-danger">Cancelar</a>
          </div>
        </form>
      </div>
  	</div>
  </div>

</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->