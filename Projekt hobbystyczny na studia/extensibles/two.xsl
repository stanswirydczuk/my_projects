<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
  <xsl:output method="html" indent="yes"/>

  <xsl:template match="/root-element">
    <html>
      <head>
        <title>Second transformation</title>
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
        <div id="exbox2">
          <div id="exbox">
            <h1>SECOND TRANSFORMATION</h1>
            <h3>AUTHOR INFORMATION</h3>
            <xsl:call-template name="author-info"/>
            <h3>SOURCE LINKS FROM PREVIOUS TRANSFORMATION</h3>
            <xsl:apply-templates select="sources"/>
          </div>
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

  <xsl:template match="sources">
    <div class="shadow">
      <xsl:copy>
        <p><xsl:apply-templates select="@* | node()"/></p>
      </xsl:copy>
    </div>
  </xsl:template>

  <xsl:template match="description">
    <xsl:copy>
      <xsl:apply-templates select="@* | node()"/>
    </xsl:copy>
  </xsl:template>

  <xsl:template match="url">
    <a class="source-links" href="{.}">
      <xsl:apply-templates select="@* | node()"/>
    </a>
  </xsl:template>

  <xsl:template match="wiki">
    <xsl:copy-of select="."/>
  </xsl:template>

  <xsl:template name="author-info">
    <div class="shadow">
      <xsl:element name="author-info">
        <xsl:attribute name="elements">2</xsl:attribute>
    
        <table border="0">
          <tr>
            <td style="background-color: #1f2226; border-color: white;">Name</td>
            <td style="background-color: #1f2226; border-color: white;">Surname</td>
          </tr>
          <tr>
            <xsl:element name="name">
              <xsl:attribute name="elements">2</xsl:attribute>
              <td><xsl:element name="first">Stanisław</xsl:element></td>
              <td><xsl:element name="last">Świrydczuk</xsl:element></td>
            </xsl:element>
          </tr>
    
          <tr>
            <td style="background-color: #1f2226; border-color: white;" colspan="3">Birthday</td>
          </tr>
          <tr>
            <xsl:element name="birth">
              <xsl:attribute name="age">18</xsl:attribute>
              <td><xsl:element name="day">07</xsl:element></td>
              <td><xsl:element name="month">03</xsl:element></td>
              <td><xsl:element name="year">2005</xsl:element></td>
            </xsl:element>
          </tr>
    
          <tr>
            <td style="background-color: #1f2226; border-color: white;" colspan="2">Hobbies</td>
          </tr>
          <tr>
            <xsl:element name="hobbies">
              <xsl:attribute name="hobbies">2</xsl:attribute>
              <td colspan="2"><xsl:element name="hobby">Computer Science</xsl:element></td>
            </xsl:element>
          </tr>
          <tr>
            <xsl:element name="hobbies">
              <xsl:attribute name="hobbies">2</xsl:attribute>
              <td colspan="2"><xsl:element name="hobby">Anime</xsl:element></td>
            </xsl:element>
          </tr>
        </table>
      </xsl:element>
    </div>
  </xsl:template>

</xsl:stylesheet>
