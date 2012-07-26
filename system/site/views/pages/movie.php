<div class="wrapper">
  	<div class="wrapper">
      	<div class="aside">
        	<div class="section">
            <img src="<?=$poster_url_large?>" width="200" align="center">
            <div class="trailer_link_container">
            	<? if(isset($youtube_id)): ?>
           			<a href="http://www.youtube.com/embed/<?=$youtube_id?>?wmode=transparent&autoplay=1&iv_load_policy=3" id="trailer" class="trailer_link"><em><b>view trailer</b></em></a>
            	<? endif; ?>
            </div>
          </div>
          
          
        </div>
        <div class="mainContent">
          <div class="row-1 section">
            <!-- .box -->
            <div class="box">
              <div class="left-top-corner">
                <div class="right-top-corner">
                  <div class="right-bot-corner">
                    <div class="left-bot-corner">
                      <div class="inner">
                        <div class="title">
                          <h3><?=$title?></h3>
                        </div>
                        <!-- .box1 -->
                        <div class="box1">
                          <div class="border-top">
                            <div class="border-right">
                              <div class="border-bot">
                                <div class="border-left">
                                  <div class="left-top-corner">
                                    <div class="right-top-corner">
                                      <div class="right-bot-corner">
                                        <div class="left-bot-corner">
                                          <div class="inner">
                                         	   <?=theaters_button($id)?> <?=dvd_button($id)?>
											
                           					<table class="movie_info" width="100%">
                           						<td height="10"></td>
                           						
                           						<? if(!is_null($runtime)): ?>
                           						<tr>
                           							<td class="th">Runtime</td>
                           							<td><?=$runtime?></td>
                           						</tr>
                           						<? endif; ?>
                           						
                           						<? if(!empty($dvd_release_date) || !empty($release_date)): ?>
                           						<tr>
                           							<td class="th">Release Date<? if(!empty($dvd_release_date) && !empty($release_date)): ?>s<? endif; ?></td>
                           							<td>
                           								<? if(!empty($release_date)): ?><strong>Theaters: </strong><?=$release_date?><? endif; ?>
                           								<? if(!empty($dvd_release_date)): ?><br><strong>DVD: </strong><?=$dvd_release_date?><? endif; ?>
                           							</td>
                           						</tr>
                           						<? endif; ?>
                           						
                           						<? if(!is_null($synopsis)): ?>
                           						<tr>
                           							<td class="th">Synopsis</td>
                           							<td><?=$synopsis?></td>
                           						</tr>
                           						<? endif; ?>
                           						
                           						<? if(!is_null($tagline)): ?>
                           						<tr>
                           							<td class="th">Tagline</td>
                           							<td><?=$tagline?></td>
                           						</tr>
                           						<? endif; ?>
                           						
                           					</table>               
                                            
                                            
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                        <!-- /.box1 -->
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box -->
          </div>
          
        </div>
      </div>
</div>