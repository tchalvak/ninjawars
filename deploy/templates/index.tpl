    {$header}
        
    <!-- Version {$version} -->

      <div id='left-column'>
            
        <div id='ninjawars-home' class='header-section'>
        	<a href='list_all_players.php' target='main'><img src='images/ninjawarslogo_75px.png' alt='ninja list' title='Go to the ninja list'></a>
        </div>
            <div>
                <a target="main" href="player.php?player={$user_id}" title='Display your player information'>{$username}</a>
            </div>
            <div id='logged-in-bar'>
                <div>
                  <span id='health-status'> </span>
                </div>
            </div>
          <div id="quick-stats" class="boxes">
            <div class="box-title centered">
              <a href="#" class="show-hide-link" onclick="toggle_visibility('quickstats-and-switch-stats');">
                Quick Stats <img class="show-hide-icon" src="images/show_and_hide.png" alt="+/-">
              </a>
            </div>
            <div id="quickstats-and-switch-stats">
              <div class="centered quickstats-container">
                <a href="quickstats.php" target="quickstats">Player</a> 
                | <a href="quickstats.php?command=viewinv" target="quickstats">Inventory</a>
              </div>
              <div id="quickstats-frame-container">
                <iframe border='0' id="quickstats" src="quickstats.php" name="quickstats">
                  <a href='quickstats.php' target='_blank'>Quick Stats</a> unavailable inside this browser window.
                </iframe>
              </div>
            </div><!-- End of quickstats and switch container -->
          </div><!-- End of quickstats section. -->
          
          <div id="actions" class="boxes active">
            <div class="box-title">
              <a href="#" class="show-hide-link" onclick="toggle_visibility('actions-menu');">
                Actions <img class="show-hide-icon" src="images/show_and_hide.png" alt="+/-">
              </a>
            </div>
            <ul class="basemenu" id="actions-menu">
              <li id='heal-link'><a href="shrine_mod.php?heal_and_resurrect=1" target="main"><img src='images/shrine.png' alt=''>Heal</a></li>
              <li>
                <ul class="submenu">
                  <li>
                    <a href="inventory_mod.php?item=Stealth%20Scroll&amp;selfTarget=1&amp;link_back=inventory"
                     target="main">Stealth</a>
                  </li>
                  <li>
                    <a href="inventory_mod.php?item=Speed%20Scroll&amp;selfTarget=1&amp;link_back=inventory" 
                    target="main">Speed</a>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        <div id="ninja-search" class="boxes active">
            <form id="player_search" action="list_all_players.php" target="main" method="get" name="player_search">
              <div>
                Find A Ninja:
                <input id="searched" type="text" maxlength="50" size="10" name="searched" class="textField">
                <input id="hide" type="hidden" name="hide" value="dead">
                <button type="submit" value="find" class="formButton">Find</button>
              </div>
            </form>
          </div>
{if $player_info.level < 2}
          <div id='helpful-info'>
            <a target='main' href='tutorial.php'>Helpful Info</a>
          </div>
{/if}
          <!-- Recent Events & Recent Mail will get put in here via javascript -->
          <div id='recent-events'></div>

      </div>  
      
      
      
      <!-- CENTRAL COLUMN STARTS HERE -->
      
      
      
      <div id='center-column'>

      
      <div id='menu-bar' class='header-section'>
        <div id='reactive-panel'>

            <div id='category-bar'>
              <ul>
                <li id='status-actions'>
                  <a href='events.php' target='main' title='See messages about whether you were attacked or other events.'>
                    <img src='/images/ninja_silhouette_50px.png' alt=''>Status
                  </a>
                </li>
                <li id='combat-actions'>
                  <a href='enemies.php' target='main' title='Check up on your enemies and see who recently attacked you.'>
                    <img src='/images/50pxShuriken.png' alt=''>Combat
                  </a>
                </li>
                <li id='village-actions'>
                  <a href='attack_player.php' target='main' title='Travel to different locations in the village.'>                  
                    <img src='/images/pagodaIcon_60px.png' alt=''>Village
                  </a>
                </li>
              </ul>
            </div>
            <div id='subcategory-bar'>
                <ul id='self-subcategory'>
                  <li><a href="stats.php" target="main" title='Your ninja strength, level, profile, etc.'>Stats</a></li>
                  <li><a href="skills.php" target="main" title='Your ninja skills &amp; abilities'>Skills</a></li>
                  <li><a href="inventory.php" target="main" title='Your items and links to use them on yourself.'>Items</a></li>
                  <!-- Profile -->
                  <!-- Settings -->
                </ul>
                <ul id='combat-subcategory'>
                  <li><a href="list_all_players.php" target="main" title='Ranked list of ninjas to attack.'>Ninjas</a></li>
                  <li><a href="clan.php" target="main" title='Clans and your clan options.'>Clans</a></li>
                  <li><a href="duel.php" target="main" title="Today's Duels">Duels</a></li>
                </ul>
                <ul id='village-subcategory'>
                  <li><a href="shop.php" target="main" title='Spend your money to get weapons.'>Shop</a></li>
                  <li><a href="work.php" target="main" title='Trade your turns to get money.'>Work</a></li>
                  <li><a href="doshin_office.php" target="main" title='Hunt bounties for money.'>Doshin <img src="images/doshin.png" alt=""></a></li>
                </ul>
            </div>
        </div>
        
      </div><!-- End of menu-bar -->


          <div id="main-frame-container"><!-- THE MAIN CONTENT DISPLAY SECTION -->
            <iframe border='0' id="main" name="main" class="main-iframe" src="{$main_src}">
              <a href='{$main_src}' target='_blank'>Main Content</a> unavailable inside this browser window.
            </iframe>
          </div><!-- End of mainFrame div -->
          
      </div> <!-- End of center-column -->




      <!-- RIGHTMOST COLUMN STARTS HERE -->


      <div id='right-column'>
      
      
        <div id='ninja-stats' class='header-section'>
        
        <span id='logout'>
            <a href="index.php?logout=true">Logout</a>
        </span>
        
        </div><!-- End of ninja-stats -->
          <div id='ninja-count-menu' class='boxes passive'>
            <!-- <a href="list_all_players.php" target="main">
              <span id='nin1'>Ni</span><span id='nin2'>nj</span><span id='nin3'>as</span> 
              <img src="images/smallArrows.png" alt="&gt;&gt;&gt;">
            </a> -->
            {$players_online} ninjas around / {$player_count} 
          </div>
          <div id='messages' class='boxes active'>
              <div>
                  <a target="main" id='message-inbox' href="messages.php">Messages<img id='messages-icon' src='images/messages.png' alt=''></a>
              </div>
              <div id='recent-mail'></div>
          </div>
        
          <div id='index-chat'>
              <div id="village-chat" class="boxes active">
                <div class="box-title centered">
                  <a href="#" class="show-hide-link" onclick="toggle_visibility('chat-and-switch');">
                    Chat <img class="show-hide-icon" src="images/show_and_hide.png" alt="+/-">
                  </a>
                </div>
                <div id="chat-and-switch">
                  <div class="chat-switch centered">
                    <a id='full-chat-link' href="village.php" target="main">Full Chat <img src="images/chat.png" alt=""> </a>
                  </div>
                  <form class='chat-submit' id="post_msg" action="mini_chat.php" method="post" name="post_msg" target='mini_chat'>
                    <input id="message" type="text" size="20" maxlength="250" name="message" class="textField">
                    <input id="command" type="hidden" value="postnow" name="command">
                    <input name='chat_submit' type='hidden' value='1'>
                    <button type="submit" value="1" class="formButton">Chat</button>
                  </form>
                  <div id="mini-chat-frame-container" class='chat-collapsed'>
                    <iframe id="mini_chat" name="mini_chat" src="mini_chat.php">
                      <a href='mini_chat.php' target='_blank'>Mini Chat</a> unavailable inside this browser window.
                    </iframe>
                  </div>
                  <!-- <div id="expand-chat">
                    <a href="mini_chat.php?chatlength=360" target="mini_chat">
                      View more chat messages <img class="show-hide-icon" src="images/show_and_hide.png" alt="+/-">
                    </a>
                  </div>
                  -->
                </div>
              </div>
          </div> <!-- End of index-chat --> 

          <div id="music" class="boxes passive">
            <object type="audio/x-midi" data="files/music/samsho.mid" id="music-player">
              <param name="src" value="files/music/samsho.mid">
              <param name="autoplay" value="true">
              <param name="autoStart" value="0">
              <a href="files/music/samsho.mid">
                Music <img class="play-button" src="images/bullet_triangle_green.png" alt="&gt;">
              </a>
            </object>
          </div>          
          
      </div> <!-- End of right column -->
      <div id='push'></div>
      <div id='index-footer'>
        <!-- Substitute dynamic "catchphrases" here eventually -->
        <!-- "There was going to be a NinjaWars2, but NinjaWars1 stabbed it." -->
        <!-- "Helping ninjas stab people since 2003." | -->
        Annoy the Emperor, kill Samurai. |
        <a href="tutorial.php" target="main">Help</a> |
        <a href="rules.php" target="main">Rules</a> |
        <a href='staff.php' target='main'>Staff</a> |
        <a href="http://ninjawars.proboards.com/index.cgi?board=ann" target="_blank" class="extLink">News</a> |
        <a href="http://ninjawars.proboards.com/index.cgi?action=calendar" target="_blank" class="extLink">Calendar</a> |
        <a href="http://ninjawars.pbworks.com/" target="_blank" class="extLink">Wiki</a> |
        <a href="http://ninjawars.proboards.com" target="_blank" class="extLink">Forum</a> |
        <a href="http://getsatisfaction.com/ninjawars" target="_blank" class="extLink">Feedback</a>
      </div>
      
    
<!-- Validated as of Oct, 2009 -->

    <!-- Version: {$version} -->

  </body>
</html>
