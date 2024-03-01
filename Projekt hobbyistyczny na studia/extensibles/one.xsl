<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="html" indent="yes"/>

  <xsl:template match="/root-element">
    <html>
      <head>
        <title>More about</title>
        <script src="../plugin/jquery-1.8.3.min.js"></script>
        <script src="../jquery-ui/jquery-ui.js"></script>
        <link rel="stylesheet" type="text/css" href="../resources/style.css"/>
        <link rel="stylesheet" href="../jquery-ui/jquery-ui.css"/>
        <link rel="stylesheet" href="../jquery-ui/jquery-ui.structure.css"/>
        <link rel="stylesheet" href="../jquery-ui/jquery-ui.theme.css"/>
        <script>
          $(document).ready(function () { 
            $("#dialog").dialog({
                autoOpen: false, 
                modal: true, 
                width: 400,
                buttons: {
                    "Ok": function() {
                        $(this).dialog("close");
                    }
                }
            });
    
            $("#openDialogButton").on("click", function() {
                $("#dialog").dialog("open");
            });
          });
        </script>
        <script src="../js/script.js"></script>
      </head>
      <body id="exbody">
        <nav>
          <div class="navbar">
            <div class="dropdown">
              <button class="dropbtn">Fun facts
                <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512"><style>svg{fill:#fafcff}</style><path d="M137.4 374.6c12.5 12.5 32.8 12.5 45.3 0l128-128c9.2-9.2 11.9-22.9 6.9-34.9s-16.6-19.8-29.6-19.8L32 192c-12.9 0-24.6 7.8-29.6 19.8s-2.2 25.7 6.9 34.9l128 128z"/></svg>
              </button>
              <div class="dropdown-content">
                <a href="../html/funfacts.html">Fun facts</a>
                <a href="extensibles/one.xml">More about</a>
                <a href="../gallery/php/gallery.php">Gallery</a>
                <a href="../gallery/php/selected_images.php">Selected Images</a>
              </div>
            </div>
            <a class="main" href="../index.html">MAIN PAGE</a>
            <a href="../html/ranking.html">Ranking</a>
          </div>
        </nav>
        <div class="bdiv">
          <a href="#footer" class="bottom">GO TO BOTTOM</a>
        </div>
        <div id="exbox2">
          <div id="exbox">
            <h1>More about anime</h1>
            <xsl:apply-templates/>
          </div>
        </div>
        <div class="tdiv">
          <a href="#top" class="top">GO TO TOP</a>
        </div>  
        <footer>
          <div id="footer">
            <div id="dialog" title="Document validations">   
              <h2>Validation of the HTML documents:</h2>
              <img class="valload" src="../images/compressed-gallery/gif2.gif" alt="Validation chart of the HTML documents"/>
              <h2>Validation of the CSS documents:</h2>
              <img class="valload" src="../images/compressed-gallery/gif2.gif" alt="Validation chart of the CSS documents"/>
            </div>
            <button id="openDialogButton">Document validations from 12.11.23</button>
            <button id="add">Show validation links!</button>
            <button id="opener">Show validation links!</button>
            <button id="closer">Remove validation links!</button>
          </div>
        </footer>
      </body>
    </html>
  </xsl:template>

  <xsl:template match="author">
    <div class="shadow">
      <h2>Author Information</h2>
      <table id="extable" border="1">
        <tr>
          <th>Name</th>
          <th>Surname</th>
          <th>Index</th>
          <th>Email</th>
        </tr>
        <tr>
          <td><xsl:value-of select="name"/></td>
          <td><xsl:value-of select="surname"/></td>
          <td><xsl:value-of select="index"/></td>
          <td><xsl:value-of select="email"/></td>
        </tr>
      </table>
    </div>
  </xsl:template>

  <xsl:template name="articleOne" match="article[@articleNumber='one']">
    <div class="shadow">
      <h3>Article One</h3>
      <xsl:apply-templates/>
      <xsl:if test="count(ranking/spot/title[@haveSeen='Yes']) > 0">
        <h4>Anime titles seen by the author</h4>
        <xsl:for-each select="ranking/spot[title[@haveSeen='Yes']]">
          <xsl:sort select="number" data-type="number"/>
          <p><xsl:value-of select="number"/><xsl:value-of select="title"/></p>
        </xsl:for-each>
      </xsl:if>
    </div>
  </xsl:template>
  
  <xsl:template match="ranking">
    <ol>
      <xsl:apply-templates select="spot">
        <xsl:sort select="number" data-type="number"/>
      </xsl:apply-templates>
    </ol>
  </xsl:template>
  
  <xsl:template match="spot">
    <li>
      <xsl:value-of select="title"/>
    </li>
  </xsl:template>

  <xsl:template match="article[@articleNumber='one']/beginning">
    <p><xsl:value-of select="."/></p>
  </xsl:template>

  <xsl:template match="article[@articleNumber='one']/ending">
    <p><xsl:value-of select="."/></p>
  </xsl:template>

  <xsl:template name="articleTwo" match="article[@articleNumber='two']">
    <div class="shadow">
      <h3>Article Two</h3>
      <xsl:apply-templates/>
      <xsl:choose>
        <xsl:when test="count(studios/studio) > 0">
          <p class="analyzer">[ARTICLE ANALYZER] Article Two features <xsl:value-of select="count(studios/studio)"/> studios.</p>
        </xsl:when>
        <xsl:otherwise>
          <p class="analyzer">[ARTICLE ANALYZER] Article Two does not feature any studios.</p>
        </xsl:otherwise>
      </xsl:choose>
    </div>
  </xsl:template>

  <xsl:template match="article[@articleNumber='two']/beginning">
    <p><xsl:value-of select="."/></p>
  </xsl:template>

  <xsl:template match="article[@articleNumber='two']/studios/studio">
    <li><xsl:value-of select="name"/><xsl:value-of select="description"/></li>
  </xsl:template>

  <xsl:template match="article[@articleNumber='two']/ending">
    <p><xsl:value-of select="."/></p>
  </xsl:template>

  <xsl:template name="images" match="images">
    <div class="shadow">
      <h3>Gallery</h3>
      <div id="image-box">
        <xsl:apply-templates select="image"/>
      </div>
    </div>
  </xsl:template>

  <xsl:template match="image">
    <img class="images" src="../images/other-images/{filename}" alt="{description}" />
  </xsl:template>

  <xsl:template match="popularity">
    <div class="shadow">
      <xsl:apply-templates select="description"/>
      <xsl:apply-templates select="countries"/>
    </div>
  </xsl:template>

  <xsl:template match="description">
    <h3>
      <xsl:value-of select="."/>
    </h3>
  </xsl:template>

  <xsl:template match="countries">
    <xsl:call-template name="processCountries">
      <xsl:with-param name="countryString" select="."/>
      <xsl:with-param name="position" select="1"/>
    </xsl:call-template>
  </xsl:template>

  <xsl:template name="processCountries">
    <xsl:param name="countryString"/>
    <xsl:param name="position"/>

    <xsl:variable name="currentCountry" select="normalize-space(substring-before(concat($countryString, ' '), ' '))"/>

    <xsl:if test="$currentCountry != ''">
      <h4>
        <xsl:value-of select="$position"/>
        <xsl:text>. </xsl:text>
        <xsl:value-of select="$currentCountry"/>
      </h4>

      <xsl:choose>
        <xsl:when test="$position = 1">
          <p>Number 1 country regarding anime popularity.</p>
        </xsl:when>
        <xsl:when test="$position = 2">
          <p>Number 2 country regarding anime popularity.</p>
        </xsl:when>
        <xsl:when test="$position = 3">
          <p>Number 3 country regarding anime popularity.</p>
        </xsl:when>
        <xsl:otherwise>
          <p>Anime is still popular in these countries but not as prevalent as in the top 3 countries.</p>
        </xsl:otherwise>
      </xsl:choose>

      <xsl:call-template name="processCountries">
        <xsl:with-param name="countryString" select="substring-after($countryString, ' ')"/>
        <xsl:with-param name="position" select="$position + 1"/>
      </xsl:call-template>
    </xsl:if>
  </xsl:template>

  <xsl:template name="sources" match="sources">
    <div id="shadow">
      <h4>Sources</h4>
      <ul>
        <li>
          <a class="source-links" href="{url}" target="_blank"><xsl:value-of select="description"/></a>
        </li>
        <xsl:apply-templates select="wiki/url"/>
      </ul>
    </div>
  </xsl:template>

  <xsl:template match="wiki/url">
    <li>
      <a class="source-links" href="{.}" target="_blank"><xsl:value-of select="."/></a>
    </li>
  </xsl:template>

</xsl:stylesheet>
