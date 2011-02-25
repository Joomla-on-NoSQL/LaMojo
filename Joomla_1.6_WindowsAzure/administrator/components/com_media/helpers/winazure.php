<?php
class WinAzureHelper
{
	public static $win_azure_conn = null;

	public static function initialize()
	{
		require_once('components/com_media/includes/Microsoft/WindowsAzure/Storage.php');
		require_once('components/com_media/includes/Microsoft/WindowsAzure/Storage/Blob.php');
		$config = JFactory::getConfig();
		
		try{
			$usePathStyleUri = false;
			$retryPolicy = Microsoft_WindowsAzure_RetryPolicy_RetryPolicyAbstract::retryN(10, 250);
			$host = Microsoft_WindowsAzure_Storage::URL_CLOUD_BLOB;
			self::$win_azure_conn = new Microsoft_WindowsAzure_Storage_Blob($host, $config->getValue('cloud_acc_name'), $config->getValue('secret_key'),
			$usePathStyleUri,
			$retryPolicy);
		}catch (Microsoft_WindowsAzure_Exception $ex)
		{
			echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
		}
		catch (Microsoft_Http_Transport_Exception $ex)
		{
			echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
		}
	}

	public static function createFolder($container, $access = 'public')
	{
		try{
			if(self::$win_azure_conn->isValidContainerName($container))
			{
				if(!self::$win_azure_conn->containerExists($container))
				{
					$result = self::$win_azure_conn->createContainer($container);
					if($access == 'public') //default is PRIVATE
					{
						self::$win_azure_conn->setContainerAcl($container, Microsoft_WindowsAzure_Storage_Blob::ACL_PUBLIC);
					}
				}
			}
		}catch (Microsoft_WindowsAzure_Exception $ex)
		{
			echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
		}
		catch (Microsoft_Http_Transport_Exception $ex)
		{
			echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
		}
	}

	public static function listContainers()
	{
		try{
			return self::$win_azure_conn->listContainers();
		}catch (Microsoft_WindowsAzure_Exception $ex)
		{
			echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
		}
		catch (Microsoft_Http_Transport_Exception $ex)
		{
			echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
		}
	}

	public static function deleteContainer($container)
	{
		try{
				if(self::$win_azure_conn->containerExists($container))
				{
					self::$win_azure_conn->deleteContainer($container);
				}
			
		}catch (Microsoft_WindowsAzure_Exception $ex)
		{
			echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
		}
		catch (Microsoft_Http_Transport_Exception $ex)
		{
			echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
		}
	}

	public static function createBlob($container, $file_name, $file_path)
	{
		try{
			self::$win_azure_conn->putBlob($container, $file_name, $file_path);
		}catch (Microsoft_WindowsAzure_Exception $ex)
		{
			echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
		}
		catch (Microsoft_Http_Transport_Exception $ex)
		{
			echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
		}
	}
	
	public static function createBlobData($container, $file_name)
	{
		try{
			self::$win_azure_conn->putBlobData($container, $file_name);
		}catch (Microsoft_WindowsAzure_Exception $ex)
		{
			echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
		}
		catch (Microsoft_Http_Transport_Exception $ex)
		{
			echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
		}
	}
	
	public static function listBlobs($container)
	{
		try{
			return self::$win_azure_conn->listBlobs($container);
		}catch (Microsoft_WindowsAzure_Exception $ex)
		{
			echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
		}
		catch (Microsoft_Http_Transport_Exception $ex)
		{
			echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
		}
	}
	
	public static function getBaseUrl()
	{
		try{
			return self::$win_azure_conn->getBaseUrl();
		}catch (Microsoft_WindowsAzure_Exception $ex)
		{
			echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
		}
		catch (Microsoft_Http_Transport_Exception $ex)
		{
			echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
		}
	}
	
	public static function getBlobData($container, $file_name)
	{
		try{
			return self::$win_azure_conn->getBlobData($container, $file_name);
		}catch (Microsoft_WindowsAzure_Exception $ex)
		{
			echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
		}
		catch (Microsoft_Http_Transport_Exception $ex)
		{
			echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
		}
	}
	
	public static function getBlobFile($container, $file_name, $temp_file_name)
	{
		try{
			return self::$win_azure_conn->getBlob($container, $file_name, $temp_file_name);
		}catch (Microsoft_WindowsAzure_Exception $ex)
		{
			echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
		}
		catch (Microsoft_Http_Transport_Exception $ex)
		{
			echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
		}
	}
	
	public static function getBlobProperties($container, $file_name)
	{
		try{
			return self::$win_azure_conn->getBlobProperties($container, $file_name);
		}catch (Microsoft_WindowsAzure_Exception $ex)
		{
			echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
		}
		catch (Microsoft_Http_Transport_Exception $ex)
		{
			echo "<p style='color: red'>Windows Azure Blob Service: Exception: \"{$ex->getMessage()}\"<p/>";
		}
	}
}