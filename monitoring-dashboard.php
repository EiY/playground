<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <title>monitoring dashboard</title>
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.2/css/all.min.css'>
    <style>
      @import url("https://fonts.googleapis.com/css?family=Raleway:200,400|PT+Mono&display=swap");body{margin:0;font-family:"Raleway",sans-serif}
      .color-box{position:absolute;display:flex;width:250px;height:50px;bottom:0;right:0;font-size:12px;margin:8px}
      #dashboard-container{position:relative;margin:5% auto;width:750px;height:550px;border:1px solid #D8DBE2}
      .leftside-menu{position:absolute;height:100%;width:20%;background:#D8DBE2;box-shadow:2px 2px 15px rgba(73,88,103,0.3);text-align:center}
      .avatar{margin-top:55%;margin-bottom:25%;display:flex;flex-direction:column;align-items:center}
      .avatar p{color:#495867;font-size:.8em;margin-top:15px}
      .icon{width:55px;height:55px;background:#B7CEE8;border-radius:50%}
      .icon p{color:#FFFFFF;font-size:1.7em;margin:0;margin-top:12px}
      li{list-style-type:none;color:#495867;margin:0;padding:15px;text-transform:uppercase;font-size:.8em;letter-spacing:1px;cursor:pointer}
      li:hover{background:#FFFFFF;color:#577399;transition:all 0.3s ease}
      .active{background:#B7CEE8;color:#FFFFFF;width:140px;margin-left:-10px;box-shadow:1px 1px 3px rgba(73,88,103,0.3);transition:all 0.3s ease}
      .active:hover{background:#FFFFFF;color:#495867;transition:all 0.3s ease}
      h1{margin:0;font-size:1.15em;color:#FFFFFF;letter-spacing:2px;text-transform:uppercase}
      p{color:#577399;margin:0;text-transform:uppercase;letter-spacing:2px;font-size:0.8em}
      a{cursor:pointer;padding:3px}
      a:hover{background:#B7CEE8;color:#FFFFFF;transition:all 0.3s ease}
      .time{font-size:1.8em;font-family:"PT Mono",monospace;margin-top:-30px;margin-bottom:25px;color:#B7CEE8;background:#FFFFFF;padding:7px}
      .main-grid{margin-top:3.5%;width:70%;margin-left:25%;background:#FFFFFF;height:90%;display:grid;grid-gap:10px;grid-template-columns:repeat(2,1fr);grid-template-areas:"one four" "one five" "two five" "two five" "three six" "three six" "three six"}
      .item{border:1px solid #D8DBE2}
      .one{grid-area:one;grid-column:1 / 2;background:#B7CEE8;display:flex;flex-direction:column;align-items:center;justify-content:center}
      .one p{margin-bottom:-40px}
      .two{grid-area:two;grid-column:1}
      .music{color:#B7CEE8;text-align:center;margin-top:20px;margin-bottom:-10px}
      .info{height:5px;width:100%;margin-bottom:-40px;background:rgba(255,255,255,0.85);transform:translateY(35px);transition:all .5s ease-in-out}
      .progress-bar{height:5px;width:70%;margin:6% auto;background:#e7e9ed;border-radius:10px;font-family:"Raleway",sans-serif}
      .fill{background-color:#B7CEE8;width:40%;height:0.3rem;border-radius:2px}
      .time--current,.time--total{color:#495867;font-size:10px;position:absolute;margin-top:-4px}
      .time--current{left:15px}
      .time--total{right:15px}
      .currently-playing{text-align:center;margin-top:-3px}
      .song-name,.artist-name{font-family:"Raleway",sans-serif;text-transform:uppercase;margin:0}
      .song-name{font-size:.8em;letter-spacing:3px;color:#495867}
      .artist-name{font-size:.6em;letter-spacing:1.5px;color:#697e94;margin-top:5px}
      .controls{display:flex;align-items:center;font-size:.8em;justify-content:center;color:#B7CEE8;cursor:pointer}
      .controls .play{margin:15px 20px;color:#90b4dc}
      .controls .option{left:10px;position:absolute;font-size:.8em}
      .controls .add{right:10px;position:absolute;font-size:.8em}
      .controls .volume{margin-right:30px;font-size:.8em}
      .controls .shuffle{margin-left:30px;font-size:.8em}
      .play,.next,.previous,.option,.add,.volume,.shuffle{transition:all .5s ease}
      .play:hover,.next:hover,.previous:hover,.option:hover,.add:hover,.volume:hover,.shuffle:hover{color:#697e94}
      .three{grid-area:three;grid-column:1;background:#D8DBE2}
      h2{color:#FFFFFF;font-size:2.3em;text-align:center;margin-top:10px;margin-bottom:-20px}
      h2 i{font-size:.7em;margin-left:-10px}
      .weather{text-align:center;margin-top:30px;margin-bottom:-70px}
      .weather p{font-size:.7em}
      .city{color:#495867}
      .four{grid-area:four;grid-column:2;display:flex;justify-content:center;align-items:center;flex-direction:column}
      .four p{margin-top:8px}
      #date{color:#B7CEE8;font-family:"PT Mono",monospace;font-size:1.3em;font-weight:900;letter-spacing:2px}
      .five{grid-area:five;grid-column:2}
      .rooms--top{margin:20px}
      .rooms{margin-bottom:-55px}
      .rooms--btns{margin-top:30px}
      .toggle{cursor:pointer;display:inline-block;margin-bottom:13px}
      .toggle-switch{display:inline-block;background:#D8DBE2;border-radius:16px;margin-left:20px;width:40px;height:21px;position:relative;vertical-align:middle;transition:background 0.25s}
      .toggle-switch:before,.toggle-switch:after{content:""}
      .toggle-switch:before{display:block;background:linear-gradient(to bottom,#FFFFFF 0%,#eee 100%);border-radius:50%;box-shadow:0 0 0 1px rgba(0,0,0,0.25);width:17px;height:17px;position:absolute;top:2px;left:2px;transition:left 0.25s}
      .toggle:hover .toggle-switch:before{background:linear-gradient(to bottom,#fff 0%,#fff 100%);box-shadow:0 0 0 1px rgba(0,0,0,0.5)}
      .toggle-checkbox:checked + .toggle-switch{background:#B7CEE8}
      .toggle-checkbox:checked + .toggle-switch:before{left:20px}
      .toggle-checkbox{position:absolute;visibility:hidden}
      .toggle-label{font-size:0.8em;margin-left:15px;text-transform:uppercase;letter-spacing:2px;position:relative;top:2px}
      .six{grid-area:six;grid-column:2;background:#B7CEE8}
      .reminders{display:flex;position:relative}
      .reminders p{margin-top:20px;margin-left:20px}
      .reminder--btns{position:absolute;right:18px;top:17px}
      .reminder--btns i{color:#FFFFFF;cursor:pointer}
      .reminder--btns i:hover{color:#577399;transition:all 0.3s ease}
      .reminders--list{margin-top:15px;margin-bottom:-60px}
      .todo{cursor:pointer;display:inline-block}
      .todo-switch{display:inline-block;background:#FFFFFF;border-radius:50%;margin-left:20px;width:12px;height:12px;position:relative;vertical-align:middle;transition:background 0.25s}
      .todo-switch:before,.todo-switch:after{content:"";color:#FFFFFF}
      .todo-checkbox:checked + .todo-switch{background:#577399}
      .todo-checkbox:checked + .todo-switch:before{content:"\2713";color:#FFFFFF;font-size:1.3em;transition:all 0.3s ease;position:absolute;top:-10px;left:1px}
      .todo-checkbox{position:absolute;visibility:hidden}
      .todo-label{font-size:0.8em;margin-left:8px;text-transform:uppercase;letter-spacing:2px;position:relative;color:#FFFFFF;text-transform:none}
      footer{position:absolute;bottom:0;right:0;text-align:center;font-size:1em;text-transform:uppercase;padding:10px}
      footer p{text-transform:uppercase;color:#577399;font-family:"Raleway",sans-serif;letter-spacing:3px}
      footer a{font-family:"Raleway",sans-serif;text-transform:uppercase;color:#495867;text-decoration:none}
      footer a:hover{color:#B7CEE8;background:#FFFFFF}
    </style>
  </head>
  <body>
    <div id="dashboard-container">
      <div class="leftside-menu">
        <div class="avatar">
          <div class="icon"><p>EIY</p></div>
          <p>Github</p>
        </div>
        <nav class="menu">
          <li class="active">home</li>
          <li>music</li>
          <li>reminders</li>
          <li>contacts</li>
          <li>skills</li>
          <li>settings</li>
        </nav>
      </div>
      <div class="main-grid">
        <div class="item one">
          <h1 id="time" class="time"><?=date( 'g:m A' )?></h1>
          <h1 id="greeting">Good <?=($h = date( 'G' )) > 10 ? $h > 17 ? 'evening' : 'afternoon' : 'morning'?></h1>
          <p>github</p>
        </div>
        <div class="item two">
          <div class="music"><p>now playing</p></div>
          <div class="info">
            <div class="progress-bar">
              <div class="time--current">1:25</div>
              <div class="time--total">-3:25</div>
              <div class="fill"></div>
            </div>
            <div class="currently-playing">
              <h2 class="song-name">Paris in the Rain</h2>
              <h3 class="artist-name">Lauv</h3>
            </div>
            <div class="controls">
              <div class="option"><i class="fas fa-bars"></i></div>
              <div class="volume"><i class="fas fa-volume-up"></i></div>
              <div class="previous"><i class="fas fa-backward"></i></div>
              <div class="play"><i class="fas fa-play"></i></div>
              <div class="next"><i class="fas fa-forward"></i></div>
              <div class="shuffle"><i class="fas fa-random"></i></div>
              <div class="add"><i class="fas fa-plus"></i></div>
            </div>
          </div>
        </div>
        <div class="item three">
          <h2><i class="fas fa-cloud-sun"></i> 57&#176;</h2>
          <div class="weather">
            <p>cloudy skies</p>
            <p class="city">Seattle, WA</p>
          </div>
        </div>
        <div class="item four">
          <h1 id="date"><?=date( 'F j, Y' )?></h1>
          <p><a>no events today</a></p>
        </div>
        <div class="item five">
          <div class="rooms--top">
            <p>lights</p>
          </div>
          <div class="rooms">
            <div class="rooms--btns">
              <label class="toggle">
                <input class="toggle-checkbox" type="checkbox" checked>
                <div class="toggle-switch"></div>
                <span class="toggle-label">Bedroom</span>
              </label>
              <label class="toggle">
                <input class="toggle-checkbox" type="checkbox">
                <div class="toggle-switch"></div>
                <span class="toggle-label">Bathroom</span>
              </label>
              <label class="toggle">
                <input class="toggle-checkbox" type="checkbox" checked>
                <div class="toggle-switch"></div>
                <span class="toggle-label">Kitchen</span>
              </label>
              <label class="toggle">
                <input class="toggle-checkbox" type="checkbox">
                <div class="toggle-switch"></div>
                <span class="toggle-label">Living Room</span>
              </label>
              <label class="toggle">
                <input class="toggle-checkbox" type="checkbox">
                <div class="toggle-switch"></div>
                <span class="toggle-label">Study</span>
              </label>
            </div>
          </div>
        </div>
        <div class="item six">
          <div class="reminders">
            <p>reminders</p>
            <div class="reminder--btns">
              <i class="fas fa-ellipsis-h"></i>
            </div>
          </div>
          <div class="reminders--list">
            <label class="todo">
              <input class="todo-checkbox" type="checkbox" checked>
              <div class="todo-switch"></div>
              <span class="todo-label">pay bills</span>
            </label>
            <label class="todo">
              <input class="todo-checkbox" type="checkbox">
              <div class="todo-switch"></div>
              <span class="todo-label">go grocery shopping</span>
            </label>
          </div>
        </div>
      </div>
    </div>
    <footer>
      <p><a target="_blank" href="https://www.instagram.com/julieparkim">src</a>♡
    </footer>
  </body>
</html>