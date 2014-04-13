<div class="row">
	<div class="col-md-6 col-md-offset-3">
		<div class="panel panel-default" style="margin-top: 40px">
			<div class="panel-heading">
				<h2 class="panel-title">Install Gallery Plugin</h2>
			</div>
			<div class="panel-body" style="text-align: center">
				<p>Your enviorment isn't ready to use Gallery yet.</p>

				<?php echo $this->Html->link('Configure my workflow now!',
				array(
					'controller' => 'install',
					'action' => 'configure',
					'plugin' => 'gallery'
				),
				array(
					'class' => 'btn btn-success btn-lg'
				))?>

				<div class="clearfix"></div>


			</div>
			<div class="panel-footer" style="text-align: center">
				<small>We will create a config file and create 2 tables in your database.</small>
			</div>
		</div>
	</div>
</div>