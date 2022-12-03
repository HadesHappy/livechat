<?php include_once 'header.php';?>

<div class="content">

	<div class="row">
		<div class="col-md-12">

			<div class="card">
				<div class="card-body">

					<ul class="nav nav-pills nav-pills-primary nav-pills-icons justify-content-center">
                      <li class="nav-item">
                      	<a href="javascript:void(0)" data-chat="info" title="<?php echo $jkl['g40'];?>" class="nav-link lc-info active"><i class="fa fa-user"></i><?php echo $jkl['g136'];?></a>
                      </li>
                      <li class="nav-item">
                        <a href="javascript:void(0)" data-chat="edit" title="<?php echo $jkl['g47'];?>" class="nav-link lc-info"><i class="fa fa-user-edit"></i><?php echo $jkl['g287'];?></a>
                      </li>
                      <li class="nav-item">
                        <a href="javascript:void(0)" data-chat="search" title="<?php echo $jkl['s5'];?>" class="nav-link lc-info"><i class="fa fa-search"></i><?php echo $jkl['s3'];?></a>
                      </li>
                      <li class="nav-item">
                        <a href="javascript:void(0)" data-chat="files" title="<?php echo $jkl['g51'];?>" class="nav-link lc-info"><i class="fa fa-file-archive"></i><?php echo $jkl['g51'];?></a>
                      </li>
                      <li class="nav-item">
                        <a href="javascript:void(0)" data-chat="starred" title="<?php echo $jkl['g275'];?>" class="nav-link lc-info"><i class="fa fa-star"></i><?php echo $jkl['g275'];?></a>
                      </li>
                      <li class="nav-item">
                        <a href="javascript:void(0)" data-chat="faq" title="<?php echo $jkl['g340'];?>" class="nav-link lc-info"><i class="fa fa-lightbulb"></i><?php echo $jkl['g340'];?></a>
                      </li>
                      <li class="nav-item">
                        <a href="javascript:void(0)" data-chat="history" title="<?php echo $jkl['g341'];?>" class="nav-link lc-info"><i class="fa fa-history"></i><?php echo $jkl['g341'];?></a>
                      </li>
                    </ul>

                </div>
            </div>

		</div>
	</div>

	<div class="row">

	<div class="col-md-8">

		<div class="card">
        	<div class="card-header">
                <h4 class="card-title" id="content-header-title"></h4>
              </div>
              <div class="card-body">

		<!-- chat output -->
		<div class="chat-wrapper">
			<div id="chatOutput" class="direct-chat-messages"></div>
		</div>

		<div class="chat-footer">

			<!--- Input form -->
			<form name="messageInput" id="MessageInput" action="javascript:sendInput(activeConvID);">
				
				<div class="form-group">
					<label for="message"><?php echo $jkl["g135"];?></label>
					<textarea name="message" id="message" class="form-control" rows="1"></textarea>

					<a href="javascript:void(0)" id="message_btn" class="chat-send"><i class="fa fa-paper-plane"></i></a>

					<div class="emoji-picker">
						<div id="emoji"></div>
					</div>

					<?php if ($jakuser->getVar("files")) { ?>
					<div class="chat-upload">
						<i class="area fa fa-paperclip" id="cUploadDrop"></i>
					</div>
					<?php } ?>
				</div>

				<div class="row chat-extra-input">
					<div class="col-4">

						<div class="form-group">
							<label for="sendurl"><?php echo $jkl["g238"];?></label>
							<input type="text" id="sendurl" autocomplete="off" name="sendurl" placeholder="<?php echo $jkl["g238"];?>" class="form-control" />
						</div>
					</div>
					<div class="col-4">
					<?php if ($jakuser->getVar("responses") && isset($LC_RESPONSES) && is_array($LC_RESPONSES)) { ?>
					
						<div class="form-group">
							<label for="response"><?php echo $jkl["g7"];?></label>
							<select name="standard" id="response" class="selectpicker" data-live-search="true"></select>
						</div>
					
					<?php } ?>
					</div>
					<div class="col-4">
					<?php if ($jakuser->getVar("files") && isset($LC_FILES) && is_array($LC_FILES)) { ?>
						<div class="form-group">
							<label for="sendurl"><?php echo $jkl["g8"];?></label>
							<div class="input-group">
								<select name="files" id="files" class="selectpicker">
									<option value="0"><?php echo $jkl["g8"];?></option>
							
								<?php foreach($LC_FILES as $f) { ?>
									<option value="<?php echo $f["id"];?>"><?php echo $f["name"];?></option>
								<?php } ?>
							
								</select>
								<span class="input-group-btn">
								<a id="sendFile" class="btn btn-success"><?php echo $jkl["g4"].' '.$jkl["g9"];?></a>
								</span>
							</div>
						</div>
				
					<?php } ?>
					</div>
				</div>
				
				<input type="hidden" name="msgeditid" id="msgeditid">
				<input type="hidden" name="msgquoteid" id="msgquoteid">
				<input type="hidden" name="userID" id="userID" value="<?php echo $jakuser->getVar("id");?>">
				<input type="hidden" name="userName" id="userName" value="<?php echo $jakuser->getVar("username");?>">
				<input type="hidden" name="operatorName" id="operatorName" value="<?php echo $jakuser->getVar("name");?>">
				<input type="hidden" name="clientOnline" id="clientOnline" value="<?php echo $jakuser->getVar("useronlinelist");?>">
							
				</form>
				
				<div class="alert alert-info" id="client-left" style="display: none"><?php echo $jkl['g64'];?></div>

			</div>

		</div>
	</div>

	</div>
	<div class="col-md-4">
        <div class="card">
        	<div class="flex-client-info"></div>
        </div>
    </div>
</div>

</div>

<?php include_once 'footer.php';?>