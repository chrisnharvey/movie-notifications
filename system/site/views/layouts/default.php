<? $this->load->view("global/header"); ?>
    <!-- header -->
    <div id="header">
      <!-- .header-box -->
      <div class="header-box">
      	<div class="left-top-corner">
        	<div class="right-top-corner">
          	<div class="border-top"></div>
          </div>
        </div>
        <div class="indent">
        	<div class="row-1">
          	<div class="wrapper">
            	<div class="fleft"><h1><a title="movie release notifications" href="<?=site_url()?>">Movie Notifications</a></h1></div>
              <div class="fright">
              	<!-- .adv-nav -->
              	<? $this->load->view("global/right_nav"); ?>
              	<!-- /.adv-nav -->
                <? $this->load->view("global/search"); ?>
              </div>
            </div>
          </div>
          <div class="row-2">
          	<!-- .nav-box -->
          	<? $this->load->view("global/nav"); ?>
          	<!-- /.nav-box -->
          </div>
        </div>
      </div>
      <!-- /.header-box -->
    </div>
    <!-- content -->
    <div id="content">
    	<? $this->load->view($page['view']); ?>
      </div>
    <!-- footer -->
    <? $this->load->view("global/footer"); ?>
