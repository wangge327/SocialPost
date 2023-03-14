<?php include "includes/head.php" ?>

<body>
	<!-- header start -->
	{{ view("includes/header", $data); }}

	<!-- sidebar -->
	{{ view("includes/sidebar", $data); }}
	<?php
	$report_type = $_GET['r_type'];
	?>
	<div class="content">
		<div class="page-title">
			<h3>Report</h3>
		</div>
		<div class="row">
			<div class="report-menu">
				<button class="report-menu-not-ready">Not Ready</button>
				<button class="report-menu-security-deposit">Security Deposit</button>
				<button class="report-menu-security-deposit-refund">Security Deposit Refund</button>
				<button class="report-menu-rent-roll">Rent Roll-w/ Employer</button>
				<button class="report-menu-copy-vacant">Copy Vacant Units</button>
				<button class="report-menu-copy-tenants">Copy Tenants</button>
				<button class="report-menu-copy-security-deposit">Copy Security Deposit</button>
				<button class="report-menu-copy-security-deposit-list">Copy Security Deposit List</button>
				<button class="report-menu-copy-rent-roll">Copy Rent Roll</button>
				<button class="report-menu-copy-payments-applied">Copy Money In - Payments Applied</button>
				<button class="report-menu-tenant-balance">Tenant Balance</button>
				<button class="report-menu-last-48-hours-checkin">Last 48 hours checkin</button>
				<button class="report-menu-custom">Custom Report</button>
			</div>

			<?php
			if ($report_type == "")
				include "report/not_ready.php";
			if ($report_type == "copy_rent_roll")
				include "report/copy_rent_roll.php";
			if ($report_type == "copy_tenants")
				include "report/copy_tenants.php";
			if ($report_type == "copy_vacant")
				include "report/copy_vacant.php";
			if ($report_type == "security_deposit")
				include "report/security_deposit.php";
			if ($report_type == "security_deposit_refund")
				include "report/security_deposit_refund.php";
			if ($report_type == "tenant_balance")
				include "report/tenant_balance.php";
			if ($report_type == "copy_security_deposit")
				include "report/copy_security_deposit.php";
			if ($report_type == "rent_roll")
				include "report/rent_roll.php";
			if ($report_type == "copy_security_deposit_list")
				include "report/copy_security_deposit_list.php";
			if ($report_type == "copy_payments_applied")
				include "report/copy_payments_applied.php";
			if ($report_type == "last_48_hours_checkin")
				include "report/last_48_hours_checkin.php";
			else if ($report_type == "custom")
				include "report/custom.php";

			?>
		</div>
	</div>

	<?php include "includes/footer_new.php" ?>

	<script src="//code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
	<script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.flash.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
	<script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>
	<script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.print.min.js"></script>
	<script src="<?= url("/"); ?>assets/js/jquery-validate.min.js"></script>
	<script src="<?= url("/"); ?>assets/js/jquery-additional-methods.min.js"></script>

	<script>
		menu_active();

		$(document).ready(function() {

			$(".report-menu-not-ready").click(function() {
				location.replace("/report_old");
			});
			$(".report-menu-copy-rent-roll").click(function() {
				location.replace("/report_old?r_type=copy_rent_roll");
			});
			$(".report-menu-copy-tenants").click(function() {
				location.replace("/report_old?r_type=copy_tenants");
			});
			$(".report-menu-copy-vacant").click(function() {
				location.replace("/report_old?r_type=copy_vacant");
			});
			$(".report-menu-security-deposit").click(function() {
				location.replace("/report_old?r_type=security_deposit");
			});
			$(".report-menu-security-deposit-refund").click(function() {
				location.replace("/report_old?r_type=security_deposit_refund");
			});
			$(".report-menu-tenant-balance").click(function() {
				location.replace("/report_old?r_type=tenant_balance");
			});
			$(".report-menu-copy-security-deposit").click(function() {
				location.replace("/report_old?r_type=copy_security_deposit");
			});
			$(".report-menu-rent-roll").click(function() {
				location.replace("/report_old?r_type=rent_roll");
			});
			$(".report-menu-copy-security-deposit-list").click(function() {
				location.replace("/report_old?r_type=copy_security_deposit_list");
			});
			$(".report-menu-copy-payments-applied").click(function() {
				location.replace("/report_old?r_type=copy_payments_applied");
			});
			$(".report-menu-last-48-hours-checkin").click(function() {
				location.replace("/report_old?r_type=last_48_hours_checkin");
			});
			$(".report-menu-custom").click(function() {
				location.replace("/report_old?r_type=custom");
			});

			$('#data-table').DataTable({
				dom: 'Bfrtip',
				"bSort": false,
				buttons: [
					'copyHtml5',
					'excelHtml5',
					'csvHtml5'
				]
			});
		});

		function menu_active() {
			var r_type = "<?php echo input('r_type') ?>";
			r_type = r_type.replace(/_/gi, "-");
			$(".report-menu button").removeClass("report-active");

			if (r_type == "") {
				$(".report-menu-not-ready").addClass("report-active");
			} else {
				$(".report-menu-" + r_type).addClass("report-active");
			}

		}


		let baseUrl = '<?= url("/"); ?>';
		let csrf = '<?= csrf_token(); ?>';
	</script>
	<!-- scripts -->
	<script src="<?= url("/"); ?>assets/libs/bootstrap/js/bootstrap.min.js"></script>
	<script src="<?= url("/"); ?>assets/js//jquery.slimscroll.min.js"></script>
	<script src="<?= url("/"); ?>assets/js/simcify.js"></script>
	<!-- custom scripts -->
	<script src="<?= url("/"); ?>assets/js/app.js"></script>
	<script src="<?= url("/"); ?>assets/js/custom.js"></script>
	<script src="<?= url("/"); ?>assets/js/room.js"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
</body>

</html>