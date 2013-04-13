<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/day">
		<table>
			<xsl:for-each select="hour">
				<tr>
					<td>
						<xsl:value-of select="@number"/>
					</td>
				</tr>
			</xsl:for-each>
		</table>
    </xsl:template>

</xsl:stylesheet>