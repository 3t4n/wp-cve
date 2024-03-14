<?php
namespace WilokeEmailCreator\DataFactory\Interfaces;
interface IDataFactory
{
	public function getTemplateDetail($templateId);
	public function getTemplates();
	public function getProducts($aArgs);
	public function getPosts($aArgs);
	public function getCategories();
	public function getCustomerTemplates();
	public function getSections();
	public function getSection($categoryId);
}
