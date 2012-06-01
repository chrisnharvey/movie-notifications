    	<div class="wrapper">
      	<div class="aside">
        	<div class="section">
            <h2 class="alt">Box Office</h2>
            <ol class="list1">
              <? $count = 1; ?>
              <? foreach($box_office as $movie): ?>
            	  <li><?=anchor($movie['url'], '<span class="list-num">'.$count.'. </span> '.$movie['title'])?></li>
            	  <? $count++; ?>
              <? endforeach; ?>
            </ol>
          </div>
          <? /*
          	<div class="section">
            <h2>Recent Ratings</h2>
            <ul class="ratings-recent">
              <? $count = count($ratings); ?>
              <? foreach($ratings as $rating): ?>
	              <? if($count <= 5): ?>
		          	<li class="ratings" id="rating<?=$count?>">
		          <? else: ?>
		          	<li class="ratings hidden" id="rating<?=$count?>">
		          <? endif; ?>
		          
	                <?=anchor($rating['user']['profile_url'], img(user_pic($rating['user']['id'], 50, 50)))?>
	                <?=anchor($rating['user']['profile_url'], $rating['user']['username'])?> rated <?=anchor($rating['movie']['url'], $rating['movie']['title'])?> <?=$rating['rating']?>/10
	              </li>
	          <? $count--; ?>
	          <? endforeach; ?>
            </ul>
            <!-- <div class="alignright"><a href="#"><strong>all news</strong></a></div> -->
          </div>
        */ ?>
        </div>
        <div class="mainContent">
        	<div class="row-1 section">
            <!-- .box -->
            <div class="box alt-bg">
              <div class="left-top-corner">
                <div class="right-top-corner">
                  <div class="inner1">
                    <!-- slider -->
                    <div id="feature_list">
                      <ul id="output">
                      	<? foreach($in_theaters as $movie): ?>
	                        <li>
	                          <?=img($movie['image']['large'])?>
	                          <div class="description">
	                            <div class="indent">
	                              <h2><?=$movie['title']?></h2>
	                              <?=anchor($movie['url'], "<em><b>more</b></em>", array("class" => "link1"))?>
	                            </div>
	                          </div>
	                        </li>
                        <? endforeach; ?>
                      </ul>
                      <ul id="tabs">
                      	<? foreach($in_theaters as $movie): ?>
	                        <li>
	                        	<a href="javascript:void(0);"><?=img(array("src" => $movie['image']['thumb'], "width" => 175, "height" => 95))."<span>".$movie['title']?></a>
	                        </li>
	                    <? endforeach; ?>
                       
                      </ul>
                    </div>
                    <!-- slider -->
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box -->
          </div>
          <div class="row-2 section">
            <!-- .box -->
            <div class="box">
              <div class="left-top-corner">
                <div class="right-top-corner">
                  <div class="right-bot-corner">
                    <div class="left-bot-corner">
                      <div class="inner">
                        <div class="title">
                          <a href="/theaters">all movies</a><h3>Opening This Week</h3>
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
                                            	<? $count = 1; ?>
                                            	<? foreach($opening as $movie): ?>
                                            		<? if($count == 1): ?>
                                            		  <div class="wrapper">
		                                            	<div class="col-1">
		                                            	<?=img(array('src' => $movie['poster_url'], 'height' => '120', 'class' => 'img-indent'))?>
		                                                <div class="extra-wrap">
		                                                  <h5><?=anchor($movie['url'], $movie['title'])?></h5>
		                                                  <p><?=word_limiter($movie['synopsis'], 20)?></p>
		                                                  <div class="wrapper"><?=anchor($movie['url'], "<em><b>more</b></em>", array("class" => "link1"))?></div>
		                                                </div>
		                                                <div class="clear"></div>
		                                              </div>
		                                              
		                                            <? elseif($count == 2): ?>
		                                              <div class="col-2">
		                                              	<?=img(array('src' => $movie['poster_url'], 'height' => '120', 'class' => 'img-indent'))?>
		                                                <div class="extra-wrap">
		                                                  <h5><?=anchor($movie['url'], $movie['title'])?></h5>
		                                                  <p><?=word_limiter($movie['synopsis'], 20)?></p>
		                                                  <div class="wrapper"><?=anchor($movie['url'], "<em><b>more</b></em>", array("class" => "link1"))?></div>
		                                                </div>
		                                                <div class="clear"></div>
		                                              </div>
		                                            </div>
		                                            
		                                            <? elseif($count == 3): ?>
		                                            	<div class="line-hor"></div>
			                                            <div class="wrapper">
			                                            	<div class="col-1">
			                                              	<?=img(array('src' => $movie['poster_url'], 'height' => '120', 'class' => 'img-indent'))?>
			                                                <div class="extra-wrap">
			                                                  <h5><?=anchor($movie['url'], $movie['title'])?></h5>
			                                                  <p><?=word_limiter($movie['synopsis'], 20)?></p>
		                                                  	<div class="wrapper"><?=anchor($movie['url'], "<em><b>more</b></em>", array("class" => "link1"))?></div>
			                                                </div>
			                                                <div class="clear"></div>
			                                              </div>
		                                          <? elseif($count == 4): ?>
			                                          <div class="col-2">
		                                              	<?=img(array('src' => $movie['poster_url'], 'height' => '120', 'class' => 'img-indent'))?>
		                                                <div class="extra-wrap">
		                                                  <h5><?=anchor($movie['url'], $movie['title'])?></h5>
		                                                  <p><?=word_limiter($movie['synopsis'], 20)?></p>
		                                                  <div class="wrapper"><?=anchor($movie['url'], "<em><b>more</b></em>", array("class" => "link1"))?></div>
		                                                </div>
		                                                <div class="clear"></div>
		                                              </div>
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
          <div class="row-3 section">
          	<!-- .box2 -->
            <div class="box2">
              <div class="left-top-corner">
                <div class="right-top-corner">
                  <div class="right-bot-corner">
                    <div class="left-bot-corner">
                      <div class="inner">
                        <div class="wrapper">
                        	<div class="col-1">
                          	<h3>New DVDs This Week</h3>
                            <ul class="dvd-list">
                              <? $num = 1; ?>
                              <? foreach($new_dvds as $dvd): ?>
                              	<li><span><?=sprintf("%02d",$num)?>.</span><strong><?=anchor($dvd['url'], $dvd['title'])?></strong></li> 
							  	<? $num++ ?>
							  <? endforeach; ?>
                            </ul>
                            <div class="alignright"><a href="/dvd"><strong>all dvds</strong></a></div>
                          </div>
                          <div class="col-2">
                          	<!-- .box3 -->
                          	<div class="box3">
                            	<div class="border-top">
                              	<div class="border-right">
                                	<div class="border-bot">
                                  	<div class="border-left">
                                    	<div class="left-top-corner">
                                      	<div class="right-top-corner">
                                        	<div class="right-bot-corner">
                                          	<div class="left-bot-corner">
                                            	<div class="inner">
                                              	<h3>Top Rentals</h3>
                                                <ol>
                                                	<? foreach($top_rentals as $movie): ?>
                                                		<? if($movie == end($top_rentals)): ?>
                                                			<li class="last"><?=anchor($movie['url'], $movie['title'])?></li>
                                                		<? else: ?>
                                                			<li><?=anchor($movie['url'], $movie['title'])?></li> 
                                                		<? endif; ?>
                                                	<? endforeach; ?>
                                                </ol>
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
                          	<!-- /.box3 -->
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box2 -->
          </div>
        </div>
      </div>