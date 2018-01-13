<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:template match="/">
  <xsl:apply-templates>
    <xsl:sort select="substring(dtstart,1,4)"/> <!-- year  -->
    <xsl:sort select="substring(dtstart,5,2)"/> <!-- month -->
    <xsl:sort select="substring(dtstart,7,2)"/> <!-- day   -->
  </xsl:apply-templates>
<xsl:for-each select="vevent">
        <dtstart><xsl:value-of select="dtstart"/></dtstart>
        <summary><xsl:value-of select="summary"/></summary>
</xsl:for-each>

</xsl:template>

</xsl:stylesheet>
