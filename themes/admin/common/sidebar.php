            <div class="col-md-3">
                <div class="list-group">
                    <a href="" class="list-group-item disabled">管理面板</a>
				    <a href="<?php echo site_url('admin/site_settings');?>" class="list-group-item">基本设置</a>
					<a href="<?php echo site_url('admin/users/index');?>" class="list-group-item">用户</a>
					<a href="<?php echo site_url('admin/nodes');?>" class="list-group-item">版块节点</a>
					<a href="<?php echo site_url('admin/topics');?>" class="list-group-item">讨论话题</a>
					<a href="<?php echo site_url('admin/links');?>" class="list-group-item">链接</a>
					<a href="<?php echo site_url('admin/page');?>" class="list-group-item">单页面</a>
					<a href="<?php echo site_url('admin/db_admin/index');?>" class="list-group-item">数据库管理</a>
					<a href="http://www.startbbs.com" class="list-group-item">访问官方</a>
                </div>
		<div class="list-group">
                	<div class="panel panel-default">
                        	<div class="panel-heading">
                                        <h3 class="panel-title">统计</h3>
                                </div>
                                <div class="panel-body">
                                      <ul class="list-unstyled">
                                                            <li>最新会员：<?php echo $stats['last_username']?></li>
                                                            <li>注册会员： <?php echo $stats['total_users']?></li>
                                                            <li>今日话题： <?php echo $stats['today_topics'];?></li>
                                                            <li>昨日话题： <?php echo $stats['yesterday_topics'];?></li>
                                                            <li>话题总数： <?php echo $stats['total_topics']?></li>
                                                            <li>回复数： <?php echo $stats['total_comments']?></li>
                                      </ul>
                                 </div>
                        </div>
                </div>

            </div><!-- /.col-md-4 -->
