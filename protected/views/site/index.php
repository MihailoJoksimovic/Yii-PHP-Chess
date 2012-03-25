<?php $this->pageTitle=Yii::app()->name; ?>

<h1>Welcome to <span style="color:gold">I</span>ncredible<span style="color:gold">C</span>hess<span style="color:gold">T</span>ournament</h1>

<table border="0">
	<tr>
		<td style="width: 450px; vertical-align: top;" valign="top">
			<h2>Login</h2>

			<p>Please fill out the following form with your login credentials:</p>

			<div class="form">
			<?php $loginForm=$this->beginWidget('CActiveForm', array(
				'id'=>'login-form',
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>true,
				),
				'action' => array('site/login'),
			)); $loginModel = new LoginForm; ?>

				<p class="note">Fields with <span class="required">*</span> are required.</p>

				<div class="row">
					<?php echo $loginForm->labelEx($loginModel,'username'); ?>
					<?php echo $loginForm->textField($loginModel,'username'); ?>
					<?php echo $loginForm->error($loginModel,'username'); ?>
				</div>

				<div class="row">
					<?php echo $loginForm->labelEx($loginModel,'password'); ?>
					<?php echo $loginForm->passwordField($loginModel,'password'); ?>
					<?php echo $loginForm->error($loginModel,'password'); ?>
				</div>

				<div class="row rememberMe">
					<?php echo $loginForm->checkBox($loginModel,'rememberMe'); ?>
					<?php echo $loginForm->label($loginModel,'rememberMe'); ?>
					<?php echo $loginForm->error($loginModel,'rememberMe'); ?>
				</div>

				<div class="row buttons">
					<?php echo CHtml::submitButton('Login'); ?>
				</div>

			<?php $this->endWidget(); ?>
			</div><!-- form -->
		</td>
		<td valign="top">
			<h2>Register</h2>
			
			<div class="form">

				<?php $form=$this->beginWidget('CActiveForm', array(
					'id'=>'user-register-form',
					'enableAjaxValidation'=>false,
					'action' => array('site/register'),
				)); /* @var $form CActiveForm */ $model = new User; ?>

					<p class="note">Fields with <span class="required">*</span> are required.</p>

					<?php echo $form->errorSummary($model); ?>

					<div class="row">
						<?php echo $form->labelEx($model,'name'); ?>
						<?php echo $form->textField($model,'name'); ?>
						<?php echo $form->error($model,'name'); ?>
					</div>

					<div class="row">
						<?php echo $form->labelEx($model,'email'); ?>
						<?php echo $form->textField($model,'email'); ?>
						<?php echo $form->error($model,'email'); ?>
					</div>

					<div class="row">
						<?php echo $form->labelEx($model,'password'); ?>
						<?php echo $form->passwordField($model,'password'); ?>
						<?php echo $form->error($model,'password'); ?>
					</div>

					<div class="row">
						<?php echo $form->labelEx($model,'repeat_password'); ?>
						<?php echo $form->passwordField($model,'repeat_password'); ?>
						<?php echo $form->error($model,'repeat_password'); ?>
					</div>


					<div class="row buttons">
						<?php echo CHtml::submitButton('Submit'); ?>
					</div>

				<?php $this->endWidget(); ?>

				</div><!-- form -->
		</td>
	</tr>
</table>