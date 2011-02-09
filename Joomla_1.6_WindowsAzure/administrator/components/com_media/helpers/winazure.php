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
			self::$win_azure_conn->deleteContainer($container);
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
}