<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

    <xsl:template match="/month">
		<table>
			<xsl:for-each select="week">
				<tr>
				<xsl:for-each select="day">
					<td>
						<xsl:choose>
							<xsl:when test="@today = 'true'">
								<xsl:attribute name="class">
									today
								</xsl:attribute>
							</xsl:when>
						</xsl:choose>
						<xsl:value-of select="@number"/>
					</td>
				</xsl:for-each>
				</tr>
			</xsl:for-each>
		</table>
    </xsl:template>

</xsl:stylesheet>