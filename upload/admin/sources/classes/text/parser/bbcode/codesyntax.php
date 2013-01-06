<?php
/**
 * SyntaxHighlighter drop-in replacement for IP.Board v3.4.1
 *
 * @author 		Philip Lawrence
 * @copyright	(c) 2012 - 2013 Philip Lawrence
 * @license		http://www.invisionpower.com/company/standards.php#license
 * @package		IP.Board
 * @link		http://misterphilip.com
 * @link        https://github.com/MisterPhilip/ipb-syntaxhighlighter/
 * @version     10005
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
	public function __construct( ipsRegistry $registry, $_parent=NULL )
	{
		$this->currentBbcode	= 'codesyntax';
        $this->_parent = $_parent;
        
		// Do what is normally done in the parent __construct
        // We can't call parent::__construct since bbcode_plugin_code would overwrite the currentBbcode
		$this->registry		= $registry;
		$this->DB			= $this->registry->DB();
		$this->settings		=& $this->registry->fetchSettings();
		$this->request		=& $this->registry->fetchRequest();
		$this->lang			= $this->registry->getClass('class_localization');
		$this->member		= $this->registry->member();
		$this->memberData	=& $this->registry->member()->fetchMemberData();
		$this->cache		= $this->registry->cache();
		$this->caches		=& $this->registry->cache()->fetchCaches();
        
		$this->_parentBBcode = $_parent;
		
		/* Retrieve bbcode data */
		$bbcodeCache	= $this->cache->getCache('bbcode');
		$this->_bbcode	= $bbcodeCache[ $this->currentBbcode ];
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
        
        // Convert old tag (and outdated tags) to the new code tag
        $oldTags = array( );
        
        if( $this->settings['codesyntax_parseOld'] )
            $oldTags = array_merge( $oldTags, array( 'html', 'php', 'sql', 'xml' ) );
        
        foreach( $this->_retrieveTags() as $tag)
        {
            if( in_array( $tag, $oldTags ) )
            {
                $lang = $tag;
            }
            else
            {
                $lang = 'auto';
            }
            
            if ( stristr( $txt, '[' . $tag . ']' ) )
            {
                $txt = str_ireplace( '[' . $tag . ']', '[code=' . $lang . ':0]', $txt );
            } 
                
            if ( $tag != 'code' && stristr( $txt, '[/' . $tag . ']' ) )
            {
                $txt = str_ireplace( '[/' . $tag . ']', '[/code]', $txt );
            }
        }
        // Check for the correct class, less resource intensive than regex
        if( stristr( $txt, '_prettyXPrint' ) )
        {
            $tags = $this->_parent->getTagPositions( $txt, 'pre', array( '<' , '>' ));
            
            foreach( $tags['open'] as $id => $val )
			{
                $tagEnd = strpos( $txt, '>', $tags['openWithTag'][ $id ] );
                $openTag = substr( $txt, $tags['openWithTag'][ $id ], ( $tagEnd - $tags['openWithTag'][ $id ] + 1) );
                
                $origLength = $tags['closeWithTag'][ $id ] - $tags['openWithTag'][ $id ];
                
                
                $lang = 'php';
                $line = 0;
                
                if( preg_match( '#_lang-(\w+)#i', $openTag, $matches ) )
                    $lang = $matches[1];
                    
                if( preg_match( '#_linenums:(\d+)#i', $openTag, $matches ) )
                    $line = $matches[1];
                
                $content = html_entity_decode( substr( $txt, $tags['open'][ $id ], ( $tags['close'][ $id ] - $tags['open'][ $id ] ) ) );
                $content = $this->_colorfy( $content, array('lang' => $lang, 'lineNum' => $line) );
                
                $newLength = strlen( $content );
                
                $txt = substr_replace( $txt, $content, $tags['openWithTag'][ $id ], ( $tags['closeWithTag'][ $id ] - $tags['openWithTag'][ $id ] ) );
                
                // Update all lengths (we're cheating here!)
                $tags = $this->_parent->getTagPositions( $txt, 'pre', array( '<' , '>' ));
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
            list( $options['lang'], $options['lineNum']) = explode( ':', $option );
		}
        else
        {
            $options = $option;
        }
		
        // Make it pretty!
        return $this->_colorfy( $content, $options );
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
        if( $options['lang'] == 'auto' || $options['lang'] == 'code' )
            $options['lang'] = 'php';
        
        $finalContent = '<pre class="';
        $finalContent.= 'brush: ' . $options['lang'] . ';';
        #if( in_array( $options['lang'], array( 'php', 'css', 'js', 'jscript', 'javascript' ) ) )
        #    $finalContent.= ' html-script: true;';
        
        // Settings -----------------------------------------------
        // -> Line numbers
        $lineNums = intval( $options['lineNum'] );
        if( $lineNums > 0 )
        {
            $finalContent.= ' first-line: ' . $lineNums . ';';
        }   
        elseif( $this->settings['codesyntax_gutter'] )
        {
            $finalContent.= ' gutter: false;';
        }
        
        $finalContent.= '"> ';
        $finalContent.= preg_replace( '#(https|http|ftp)://#' , '\1&#58;//', htmlentities( $content ) );
        $finalContent.= '</pre>';
        
        // Return our parsed code
		return $finalContent;
	}

}