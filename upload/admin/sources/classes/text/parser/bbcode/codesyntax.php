<?php
/**
 * SyntaxHighlighter drop-in replacement for IP.Board v3.4.1
 *
 * @author 		Philip Lawrence
 * @copyright	(c) 2012 - 2013 Philip Lawrence
 * @license		http://www.invisionpower.com/company/standards.php#license
 * @package		IP.Board
 * @link		http://misterphilip.com
 * @version     10000
 *
 */

// Load in the current plugin class
if( !class_exists('bbcode_plugin_code') )
{
	require_once( IPS_ROOT_PATH . 'sources/classes/text/parser/bbcode/defaults.php' );/*noLibHook*/
}

class bbcode_plugin_codesyntax extends bbcode_plugin_code
{   
    
	/**
	 * {@inherit}
	 */
	public function __construct( ipsRegistry $registry, $_parent='' )
	{
		$this->currentBbcode	= 'codesyntax';
        $this->_parent = $_parent;
		
		parent::__construct( $registry, $_parent );
	}
	
	/**
	 * Do the actual replacement
	 *
	 * @access	protected
	 * @param	string		$txt	Parsed text from database to be edited
	 * @return	string				BBCode content, ready for editing
	 */
	protected function _replaceText( $txt )
	{
        // Check for the correct class, less resource intensive than regex
        if( stristr( $txt, '_prettyXPrint' ) )
        {
            // We already have the HTML for it, let's clean it up and GeSHi it
            if( preg_match_all( '#<pre[^>]*class\s?=\s?(["\'])((?:(?!\1).)*)_prettyXprint((?:(?!\1).)*)\1[^>]*>((?:(?!</pre>).)*)</pre>#is', $txt, $matches ) > 0 )
            {
                $codeboxCount = count( $matches[0] );
                
                // Loop through all of our matches
                for($i = 0; $i < $codeboxCount; $i++)
                {
                    $options = array();
                    
                    // Grab the other classes (options in this case) 
                    list($options['lang'], $options['lineNum'] ) = explode(' ', trim( $matches[2][$i] . $matches[3][$i] ) );
                    $options['lang'] = str_replace( '_lang-', '', $options['lang'] );
                    $options['lineNum'] = str_replace( '_linenums:', '', $options['lineNum'] );
                    
                    // Replace the current with the new
                    $replacement = $this->_colorfy( $matches[4][$i], $options );
                    $txt = str_replace( $matches[0][$i] , $replacement, $txt );
                }
            }
        }
        
        // Run the existing for bbcode versions
		parent::_replaceText( $txt );
        
        // Return the awesome looking code
		return $txt;
	}
    
	/**
	 * {@inherit}
	 */
	protected function _buildOutput( $content, $option )
	{
        // This is the original, used for BBCode.. which could cause issues with HTML (e.g. <br> in source)
        
		$content        = trim( $content );
		
		$content = preg_replace( '#(<br(?:[^>]+?)?>)#i', '', $content );
		$content = trim( $content );
		
		$content = str_replace( '<!-preserve.newline-->', "\n", $content );
        
        // Grab the option
        $lineNums = 1;
		$langAdd  = '';
		
		if ( !is_array( $option ) )
		{
            list( $option['lang'], $option['lineNum']) = explode( ':', $option );
		}
		
        // Make it pretty!
        return $this->__colorfy( $content, $option );
	}
    

	/**
	 * GeSHi the code
	 *
	 * @access	protected
	 * @param	string      $content   Content to prettify
	 * @param	array       $options   [Optional] code options
	 * @return	string			       Content ready for SyntaxHighlighter
	 */
	protected function _colorfy( $content, array $options )
	{
    
        if( $options['lang'] == 'code' )
            $options['lang'] = 'auto';
        
        $finalContent = '<pre class="';
        $finalContent.= 'brush: ' . $options['lang'] . ';';
        
        // Settings -----------------------------------------------
        // -> Line numbers
        $lineNums = intval( $options['lineNum'] );
        if( $lineNums > 0 )
        {
            $finalContent.= ' first-line: ' . $lineNums . ';';
        }
        
        $finalContent.= '"> ';
        $finalContent.= $content;
        $finalContent.= '</pre>';
        
        // Return our parsed code
		return $finalContent;
	}

}