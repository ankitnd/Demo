<?php
$i=0;
foreach($subdepartment as $k => $v) { ?>
	<?php foreach ($v as $key => $val) { ?>
<label>Sub Department</label>
			<select class="form-control" name="subdepart_ids" id="subdepartment">
				<option value="">Select SubDepartment</option>
				<?php foreach($val as $ld) { ?>
				<option value="<?php echo $ld->subdepartment_id; ?>"><?php echo $ld->subdepartment_name; ?></option>
			<?php } ?>
											
			</select>
<?php } ?>

<?php $i++;} ?>