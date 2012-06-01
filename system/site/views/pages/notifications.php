
  	<div class="wrapper">
        <div class="mainContent_full">
          <div class="row-1 section">
            <!-- .box -->
            <div class="box notif_box">
              <div class="left-top-corner">
                <div class="right-top-corner">
                  <div class="right-bot-corner">
                    <div class="left-bot-corner">
                      <div class="inner">
                        <div class="title">
                          <h3>Recent Notifications</h3>
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
                                          	<? $i = 1; ?>
                                          	<? foreach($notifs as $notif): ?>
	                                          	<div class="wrapper">
	                                          		<div class="col-1">
		                                              	<img src="<?=$this->movie_m->poster($notif['movie_id'])?>" alt="" class="img-indent">
		                                                <div class="extra-wrap">
		                                                  <h5><a href="<?=site_url('movie/'.$notif['movie_id'])?>"><?=$notif['title']?></a></h5>
		                                                  <p><?=$notif['notification']?></p>
		                                                  <div class="wrapper"><a href="<?=site_url('movie/'.$notif['movie_id'])?>" class="link1"><em><b>more</b></em></a></div>
		                                                </div>
		                                                <div class="clear"></div>
		                                              </div>
		                                         </div>
		                                         <? if($notif != end($notifs)): ?>
		                                         	<div class="clear">&nbsp;</div>
		                                         <? endif; ?>
	                                         <? endforeach; ?>
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
            
            <div class="box notif_box_right">
              <div class="left-top-corner">
                <div class="right-top-corner">
                  <div class="right-bot-corner">
                    <div class="left-bot-corner">
                      <div class="inner">
                        <div class="title">
                          <h3>Scheduled Notifications</h3>
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
          </div>
      
        </div>
      </div>
