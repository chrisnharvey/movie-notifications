
	<div class="wrapper">
        <div class="searchContent">
          <div class="row-1 section">
            <!-- .box -->
            <div class="box">
              <div class="left-top-corner">
                <div class="right-top-corner">
                  <div class="right-bot-corner">
                    <div class="left-bot-corner">
                      <div class="inner">
                        <div class="title">
                          <h3><?=$header?></h3>
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
                                          	<? if(!isset($results) OR empty($results)): ?>
                                          		Sorry, no results were found
                                          	<? else: ?>
                                            	<? $count = 1; ?>
                                            	<? foreach($results as $movie): ?>
                                            		<? if($count % 2 != 1): ?>
                                            				<div class="col-2">
			                                              	<img src="<?=$movie['poster_url']?>" alt="" class="img-indent" />
			                                                <div class="extra-wrap">
			                                                  <h5><?=anchor($movie['url'], $movie['title'])?> <? if ( ! empty($movie['year'])): ?><span>(<?=$movie['year']?>)</span><? endif; ?></h5>
			                                                  <p><?=word_limiter($movie['synopsis'], 50)?></p>
			                                                  <div class="wrapper"><a href="<?=$movie['url']?>" class="link1"><em><b>more</b></em></a></div>
			                                                </div>
			                                                <div class="clear"></div>
			                                              </div>
			                                            </div>
			                                            <div class="line-hor"></div>
                                            		<? else: ?>
                                            			<div class="wrapper">
	                                            			<div class="col-1">
			                                              	<img src="<?=$movie['poster_url']?>" alt="" class="img-indent" />
			                                                <div class="extra-wrap">
			                                                  <h5><?=anchor($movie['url'], $movie['title'])?> <? if ( ! empty($movie['year'])): ?><span>(<?=$movie['year']?>)</span><? endif; ?></h5>
			                                                  <p><?=word_limiter($movie['synopsis'], 50)?></p>
			                                                  <div class="wrapper"><a href="<?=$movie['url']?>" class="link1"><em><b>more</b></em></a></div>
			                                                </div>
			                                                <div class="clear"></div>
			                                              </div>
                                            		<? endif; ?>
                                            	<? $count++; ?>
                                            	<? endforeach; ?>
                                            <? endif; ?>
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