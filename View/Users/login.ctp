<h2>Login</h2>

<?php echo $this->Session->flash(); ?>

<div class="row">
	<div class="col-md-6">
		<?php
			echo $this->Form->create();
			echo $this->Form->input('login_id', array(
				'class' => 'form-control username',
				'div' => array('class' => 'form-group'),
				'placeholder' => __('enter username'),
				'type' => 'text',
			));

			echo $this->Form->input('login_pass', array(
				'class' => 'form-control password',
				'div' => array('class' => 'form-group'),
				'placeholder' => __('enter password'),
				'type' => 'password',
			));

			echo $this->Form->button(__('Login'), array(
				'class' => 'btn btn-primary',
				'div' => false,
			));

			echo $this->Form->end();
		?>
	</div>

</div>
