<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0"
                xmlns:html="http://www.w3.org/TR/REC-html40"
                xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:video="http://www.google.com/schemas/sitemap-video/1.1">
    <xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
    <xsl:template match="/">
        <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <title>XML Video Sitemap</title>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <style type="text/css">
                    body {
                        font-family:"Lucida Grande","Lucida Sans Unicode",Tahoma,Verdana;
                        font-size: 13px;
                    }

                    #header {
                        text-align: center;
                        padding-top: 14px;
                        padding-bottom: 29px;
                    }

                    h1 {
                        font-weight: normal;
                        font-size: 24px;
                        line-height: 20px;
                        color: #333333;
                    }

                    h2 {
                        font-weight: normal;
                        font-size: 13px;
                        color: #aaaaaa;
                        line-height: 10px;
                    }

                    #content {
                        background: #f8f8f8;
                        border-top: 1px solid #dddddd;
                        padding-top: 50px;
                    }

                    #content a:visited,
                    #content tr:hover a:visited {
                        color: #68009c;
                    }

                    table {
                        margin: 0 auto;
                        text-align: left;
                    }

                    tr#table-header:hover {
                        background: none;
                    }

                    tr:hover {
                        background: #ebebeb;
                    }

                    #content tr:hover a {
                        color: #6e6e6e;
                    }

                    td {
                        color: #6e6e6e;
                        font-size: 12px;
                        border-bottom: 1px solid #dddddd;
                        border-right: 1px solid #fff;
                        padding: 25px 65px;
                    }

                    th {
                        color: #333333;
                        font-size: 12px;
                        border-bottom: 1px solid #dddddd;
                        padding: 5px 50px 17px 5px;
                        text-align: center;
                    }

                    tr:nth-child(even) {
                        background-color: #e6f0d3;
                    }

                    tr:not(:first-child):hover {
                        background: #ebebeb;
                    }

                    a {
                        color: #2384c6;
                    }

                    a:hover {
                        color: #6e6e6e;
                        text-decoration: none;
                    }

                    tr td:not(:first-child) a, span {
                        display: block;
                    }

                    #footer {
                        background: #f8f8f8;
                        font-size: 13px;
                        color: #aaaaaa;
                        padding: 54px 0 20px;
                        text-align: center;
                    }
                </style>
            </head>
            <body>
                <div id="header">
                    <h1>XML Video Sitemap</h1>
                    <h2>This is a XML Video Sitemap which is supposed to be processed by <a href="http://www.google.com">Google search engine</a>.</h2>
                </div>
                <div id="content">
                    <table cellpadding="5" cellspacing="0">
                        <tr id="table-header">
                            <th>URL</th>
                            <th>Video URL</th>
                            <th>Video Title</th>
                        </tr>
                        <xsl:for-each select="sitemap:urlset/sitemap:url">
                                <xsl:for-each select="video:video">
                                    <tr>
                                        <td>
                                            <xsl:variable name="itemURL">
                                                <xsl:value-of select="../sitemap:loc"/>
                                            </xsl:variable>
                                            <a href="{$itemURL}">
                                                <xsl:value-of select="substring(../sitemap:loc, 0, 70)"/>
                                            </a>
                                        </td>
                                        <td>
                                            <xsl:variable name="itemURL">
                                                <xsl:value-of select="video:content_loc"/>
                                            </xsl:variable>
                                            <a href="{$itemURL}">
                                                <xsl:value-of select="substring(video:content_loc, 0, 70)"/>
                                            </a>
                                        </td>
                                        <td>
                                            <span>
                                                <xsl:value-of select="substring(video:title, 0, 70)"/>
                                            </span>
                                        </td>
                                    </tr>
                                </xsl:for-each>
                        </xsl:for-each>
                    </table>
                </div>
                <div id="footer">
                    Generated with <a href="https://bestwebsoft.com/products/wordpress/plugins/google-sitemap/">Sitemap</a> plugin by <a href="https://bestwebsoft.com">BestWebSoft</a>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>