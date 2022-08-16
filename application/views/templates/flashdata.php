<?php   if (!empty($messages = $this->session->flashdata('messages'))) {
            foreach ($messages as $key => $message) {
?>
    <div class="rounded-0 alert alert-<?=($key === 'error' ? 'danger' : 'success')?>" role="alert"><?=$message?></div>
<?php       }
        }
?>



