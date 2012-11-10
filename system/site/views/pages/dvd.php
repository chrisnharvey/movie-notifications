<div class="wrapper">
  	<div class="wrapper">
      	<div class="aside">
        	<div class="section">
            	<center><?=$calendar?></center>
          </div>
          
          
        </div>
        <div class="mainContent">
        	<div class="row-2 section">
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
                                            	                                            	                                            		                                            		  <div class="wrapper">
		                                        <? $count = 1; ?>
                                            	<? foreach($movies as $movie): ?>
                                            		<? if($count % 2 == 0): ?>
	                                            			<div class="col-2">
			                                              	<?=img(array('src' => $movie['poster_url'], 'height' => '120', 'class' => 'img-indent'))?>
			                                                <div class="extra-wrap">
			                                                  <h5><?=anchor($movie['url'], $movie['title'])?></h5>
			                                                  <p><?=word_limiter($movie['synopsis'], 20)?></p>
			                                                  <div class="wrapper"><?=theaters_button($movie['id'])?> <?=dvd_button($movie['id'])?> <?=anchor($movie['url'], "<em><b>more</b></em>", array("class" => "link1"))?></div>
			                                                </div>
			                                                <div class="clear"></div>
			                                              </div>
			                                            </div>
                                            		<? else: ?>
                                            			<? if($count % 2): ?>
                                            				<div class="line-hor"></div>
                                            			<? endif; ?>
	                                            		  <div class="wrapper">
			                                            	<div class="col-1">
			                                            	<?=img(array('src' => $movie['poster_url'], 'height' => '120', 'class' => 'img-indent'))?>
			                                                <div class="extra-wrap">
			                                                  <h5><?=anchor($movie['url'], $movie['title'])?></h5>
			                                                  <p><?=word_limiter($movie['synopsis'], 20)?></p>
			                                                  <div class="wrapper"><?=theaters_button($movie['id'])?> <?=dvd_button($movie['id'])?> <?=anchor($movie['url'], "<em><b>more</b></em>", array("class" => "link1"))?></div>
			                                                </div>
			                                                <div class="clear"></div>
			                                              </div>
		                                            <? endif; ?>
		                                      <? $count++; ?>
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
          </div>
        	
        </div>
      </div>
</div>