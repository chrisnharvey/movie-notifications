<div class="nav-box">
            	<div class="left">
              	<div class="right">
                	<!-- .nav -->
                	<ul class="nav">
                  	<li><?=anchor(site_url(), "<span><span>Home</span></span>", (site_url() == current_url()) ? "class='current'" : NULL)?></li>
                  	<li><?=anchor(site_url("theaters"), "<span><span>In Theaters</span></span>", (site_url("theaters") == site_url($this->uri->segment(1))) ? "class='current'" : NULL)?></li>
                  	<? if($this->session->userdata('country') != 225): ?>
                  		<li><?=anchor(site_url("dvd"), "<span><span>On DVD</span></span>", (site_url("dvd") == site_url($this->uri->segment(1))) ? "class='current'" : NULL)?></li>
                  	<? endif; ?>
                  </ul>
                	<!-- /.nav -->
                </div>
              </div>
            </div>