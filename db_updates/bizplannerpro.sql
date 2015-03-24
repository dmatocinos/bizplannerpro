-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 30, 2013 at 01:14 PM
-- Server version: 5.5.34-0ubuntu0.13.10.1
-- PHP Version: 5.5.3-1ubuntu2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bizplannerpro`
--

-- --------------------------------------------------------

--
-- Table structure for table `bp_user`
--
-- Creation: Dec 30, 2013 at 09:55 AM
--

DROP TABLE IF EXISTS `bp_user`;
CREATE TABLE IF NOT EXISTS `bp_user` (
  `user_id` int(110) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `business_plan`
--
-- Creation: Dec 30, 2013 at 09:55 AM
--

DROP TABLE IF EXISTS `business_plan`;
CREATE TABLE IF NOT EXISTS `business_plan` (
  `bp_id` int(110) NOT NULL AUTO_INCREMENT,
  `bp_name` varchar(225) NOT NULL,
  `bp_type` varchar(225) NOT NULL,
  `bp_financial_start_date` varchar(60) NOT NULL,
  `currency` varchar(60) NOT NULL,
  `bp_number_of_financial_forecast_yr` int(11) NOT NULL,
  `bp_yrs_of_monthly_financial_details` int(11) NOT NULL,
  `bp_related_expenses_in_percentage` decimal(11,2) NOT NULL,
  `bp_income_tax_in_percentage` decimal(11,2) NOT NULL,
  `bp_user_id` int(110) NOT NULL,
  PRIMARY KEY (`bp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

-- --------------------------------------------------------

--
-- Table structure for table `cash_flow_projection`
--
-- Creation: Dec 30, 2013 at 09:55 AM
-- Last update: Dec 30, 2013 at 09:55 AM
--

DROP TABLE IF EXISTS `cash_flow_projection`;
CREATE TABLE IF NOT EXISTS `cash_flow_projection` (
  `cash_fp_id` int(110) NOT NULL AUTO_INCREMENT,
  `percentage_sale` int(110) NOT NULL,
  `days_collect_payments` int(110) NOT NULL,
  `percentage_purchase` int(110) NOT NULL,
  `days_make_payments` int(110) NOT NULL,
  `cash_fp_bpid` int(110) NOT NULL,
  PRIMARY KEY (`cash_fp_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--
-- Creation: Dec 30, 2013 at 09:55 AM
--

DROP TABLE IF EXISTS `employee`;
CREATE TABLE IF NOT EXISTS `employee` (
  `emplye_id` int(110) NOT NULL AUTO_INCREMENT,
  `emplye_name` varchar(225) NOT NULL,
  `employee_start_date` varchar(110) NOT NULL,
  `emplye_type` varchar(255) NOT NULL,
  `employee_bp_id` int(110) NOT NULL,
  PRIMARY KEY (`emplye_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=111 ;

-- --------------------------------------------------------

--
-- Table structure for table `employee_12_month_plan`
--
-- Creation: Dec 30, 2013 at 09:55 AM
--

DROP TABLE IF EXISTS `employee_12_month_plan`;
CREATE TABLE IF NOT EXISTS `employee_12_month_plan` (
  `mpp_id` int(110) NOT NULL AUTO_INCREMENT,
  `month_01` decimal(11,2) NOT NULL DEFAULT '0.00',
  `month_02` decimal(11,2) NOT NULL DEFAULT '0.00',
  `month_03` decimal(11,2) NOT NULL DEFAULT '0.00',
  `month_04` decimal(11,2) NOT NULL DEFAULT '0.00',
  `month_05` decimal(11,2) NOT NULL DEFAULT '0.00',
  `month_06` decimal(11,2) NOT NULL DEFAULT '0.00',
  `month_07` decimal(11,2) NOT NULL DEFAULT '0.00',
  `month_08` decimal(11,2) NOT NULL DEFAULT '0.00',
  `month_09` decimal(11,2) NOT NULL DEFAULT '0.00',
  `month_10` decimal(11,2) NOT NULL DEFAULT '0.00',
  `month_11` decimal(11,2) NOT NULL DEFAULT '0.00',
  `month_12` decimal(11,2) NOT NULL DEFAULT '0.00',
  `financial_yr_forecast` year(4) NOT NULL,
  `employee_id` int(110) NOT NULL,
  PRIMARY KEY (`mpp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=109 ;

-- --------------------------------------------------------

--
-- Table structure for table `employee_financial_forecast`
--
-- Creation: Dec 30, 2013 at 09:55 AM
--

DROP TABLE IF EXISTS `employee_financial_forecast`;
CREATE TABLE IF NOT EXISTS `employee_financial_forecast` (
  `eff_id` int(110) NOT NULL AUTO_INCREMENT,
  `financial_year` int(110) NOT NULL,
  `total_per_yr` int(110) NOT NULL,
  `related_expenses` int(110) NOT NULL,
  `pay_per_year` binary(1) NOT NULL DEFAULT '0',
  `employee_id` int(110) NOT NULL,
  PRIMARY KEY (`eff_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=218 ;

-- --------------------------------------------------------

--
-- Table structure for table `executive_summary`
--
-- Creation: Dec 30, 2013 at 09:55 AM
--

DROP TABLE IF EXISTS `executive_summary`;
CREATE TABLE IF NOT EXISTS `executive_summary` (
  `exe_sum_id` int(110) NOT NULL,
  `who_we_are` text NOT NULL,
  `what_we_sell` text NOT NULL,
  `who_we_sell_to` text NOT NULL,
  `financial_summary` text NOT NULL,
  `exe_sum_bpid` int(110) NOT NULL,
  PRIMARY KEY (`exe_sum_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `expenditure`
--
-- Creation: Dec 30, 2013 at 09:55 AM
--

DROP TABLE IF EXISTS `expenditure`;
CREATE TABLE IF NOT EXISTS `expenditure` (
  `exp_id` int(110) NOT NULL AUTO_INCREMENT,
  `expenditure_name` varchar(225) NOT NULL,
  `expenditure_start_date` varchar(110) NOT NULL,
  `expenditure_bp_id` int(110) NOT NULL,
  PRIMARY KEY (`exp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Table structure for table `expenditure_12_month_plan`
--
-- Creation: Dec 30, 2013 at 09:55 AM
--

DROP TABLE IF EXISTS `expenditure_12_month_plan`;
CREATE TABLE IF NOT EXISTS `expenditure_12_month_plan` (
  `epp_id` int(110) NOT NULL AUTO_INCREMENT,
  `month_01` decimal(11,2) NOT NULL DEFAULT '0.00',
  `month_02` decimal(11,2) NOT NULL DEFAULT '0.00',
  `month_03` decimal(11,2) NOT NULL DEFAULT '0.00',
  `month_04` decimal(11,2) NOT NULL DEFAULT '0.00',
  `month_05` decimal(11,2) NOT NULL DEFAULT '0.00',
  `month_06` decimal(11,2) NOT NULL DEFAULT '0.00',
  `month_07` decimal(11,2) NOT NULL DEFAULT '0.00',
  `month_08` decimal(11,2) NOT NULL DEFAULT '0.00',
  `month_09` decimal(11,2) NOT NULL DEFAULT '0.00',
  `month_10` decimal(11,2) NOT NULL DEFAULT '0.00',
  `month_11` decimal(11,2) NOT NULL DEFAULT '0.00',
  `month_12` decimal(11,2) NOT NULL DEFAULT '0.00',
  `financial_yr_forecast` year(4) NOT NULL,
  `expenditure_id` int(110) NOT NULL,
  PRIMARY KEY (`epp_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

-- --------------------------------------------------------

--
-- Table structure for table `expenditure_financial_forecast`
--
-- Creation: Dec 30, 2013 at 09:55 AM
--

DROP TABLE IF EXISTS `expenditure_financial_forecast`;
CREATE TABLE IF NOT EXISTS `expenditure_financial_forecast` (
  `exff_id` int(110) NOT NULL AUTO_INCREMENT,
  `financial_year` int(110) NOT NULL,
  `total_per_yr` int(110) NOT NULL,
  `related_expenses` int(110) NOT NULL,
  `pay_per_year` binary(1) NOT NULL DEFAULT '0',
  `expenditure_id` int(110) NOT NULL,
  PRIMARY KEY (`exff_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=91 ;

-- --------------------------------------------------------

--
-- Table structure for table `loan_investment`
--
-- Creation: Dec 30, 2013 at 09:55 AM
--

DROP TABLE IF EXISTS `loan_investment`;
CREATE TABLE IF NOT EXISTS `loan_investment` (
  `li_id` int(110) NOT NULL AUTO_INCREMENT,
  `loan_invest_name` varchar(225) NOT NULL,
  `type_of_funding` varchar(225) NOT NULL,
  `loan_invest_interest_rate` decimal(11,2) NOT NULL,
  `loan_invest_bp_id` int(110) NOT NULL,
  PRIMARY KEY (`li_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `loan_investment_12m_payment`
--
-- Creation: Dec 30, 2013 at 09:55 AM
--

DROP TABLE IF EXISTS `loan_investment_12m_payment`;
CREATE TABLE IF NOT EXISTS `loan_investment_12m_payment` (
  `limp_id` int(110) NOT NULL AUTO_INCREMENT,
  `limp_month_01` int(110) NOT NULL DEFAULT '0',
  `limp_month_02` int(110) NOT NULL DEFAULT '0',
  `limp_month_03` int(110) NOT NULL DEFAULT '0',
  `limp_month_04` int(110) NOT NULL DEFAULT '0',
  `limp_month_05` int(110) NOT NULL DEFAULT '0',
  `limp_month_06` int(110) NOT NULL DEFAULT '0',
  `limp_month_07` int(110) NOT NULL DEFAULT '0',
  `limp_month_08` int(110) NOT NULL DEFAULT '0',
  `limp_month_09` int(110) NOT NULL DEFAULT '0',
  `limp_month_10` int(110) NOT NULL DEFAULT '0',
  `limp_month_11` int(110) NOT NULL DEFAULT '0',
  `limp_month_12` int(110) NOT NULL DEFAULT '0',
  `limp_loan_investment_id` int(110) NOT NULL DEFAULT '0',
  PRIMARY KEY (`limp_id`),
  KEY `limp_loan_investment_id` (`limp_loan_investment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- RELATIONS FOR TABLE `loan_investment_12m_payment`:
--   `limp_loan_investment_id`
--       `loan_investment` -> `li_id`
--

-- --------------------------------------------------------

--
-- Table structure for table `loan_investment_12m_received`
--
-- Creation: Dec 30, 2013 at 09:55 AM
--

DROP TABLE IF EXISTS `loan_investment_12m_received`;
CREATE TABLE IF NOT EXISTS `loan_investment_12m_received` (
  `limr_id` int(110) NOT NULL AUTO_INCREMENT,
  `limr_month_01` int(110) NOT NULL DEFAULT '0',
  `limr_month_02` int(110) NOT NULL DEFAULT '0',
  `limr_month_03` int(110) NOT NULL DEFAULT '0',
  `limr_month_04` int(110) NOT NULL DEFAULT '0',
  `limr_month_05` int(110) NOT NULL DEFAULT '0',
  `limr_month_06` int(110) NOT NULL DEFAULT '0',
  `limr_month_07` int(110) NOT NULL DEFAULT '0',
  `limr_month_08` int(110) NOT NULL DEFAULT '0',
  `limr_month_09` int(110) NOT NULL DEFAULT '0',
  `limr_month_10` int(110) NOT NULL DEFAULT '0',
  `limr_month_11` int(110) NOT NULL DEFAULT '0',
  `limr_month_12` int(110) NOT NULL DEFAULT '0',
  `limr_loan_investment_id` int(110) NOT NULL DEFAULT '0',
  PRIMARY KEY (`limr_id`),
  KEY `limr_loan_investment_id` (`limr_loan_investment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- RELATIONS FOR TABLE `loan_investment_12m_received`:
--   `limr_loan_investment_id`
--       `loan_investment` -> `li_id`
--

-- --------------------------------------------------------

--
-- Table structure for table `loan_investment_payment_f_yrs`
--
-- Creation: Dec 30, 2013 at 09:55 AM
--

DROP TABLE IF EXISTS `loan_investment_payment_f_yrs`;
CREATE TABLE IF NOT EXISTS `loan_investment_payment_f_yrs` (
  `lip_id` int(110) NOT NULL AUTO_INCREMENT,
  `lip_year` varchar(255) NOT NULL,
  `lip_total_per_yr` int(110) NOT NULL,
  `lip_loan_investment_id` int(110) NOT NULL,
  PRIMARY KEY (`lip_id`),
  KEY `lip_loan_investment_id` (`lip_loan_investment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- RELATIONS FOR TABLE `loan_investment_payment_f_yrs`:
--   `lip_loan_investment_id`
--       `loan_investment` -> `li_id`
--

-- --------------------------------------------------------

--
-- Table structure for table `loan_investment_received_f_yrs`
--
-- Creation: Dec 30, 2013 at 09:55 AM
--

DROP TABLE IF EXISTS `loan_investment_received_f_yrs`;
CREATE TABLE IF NOT EXISTS `loan_investment_received_f_yrs` (
  `lir_id` int(110) NOT NULL AUTO_INCREMENT,
  `lir_year` varchar(255) NOT NULL,
  `lir_total_per_yr` int(110) NOT NULL,
  `lir_loan_investment_id` int(110) NOT NULL,
  PRIMARY KEY (`lir_id`),
  KEY `lir_loan_investment_id` (`lir_loan_investment_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- RELATIONS FOR TABLE `loan_investment_received_f_yrs`:
--   `lir_loan_investment_id`
--       `loan_investment` -> `li_id`
--

-- --------------------------------------------------------

--
-- Table structure for table `major_purchases`
--
-- Creation: Dec 30, 2013 at 09:55 AM
-- Last update: Dec 30, 2013 at 09:55 AM
--

DROP TABLE IF EXISTS `major_purchases`;
CREATE TABLE IF NOT EXISTS `major_purchases` (
  `mp_id` int(110) NOT NULL AUTO_INCREMENT,
  `mp_name` varchar(225) COLLATE utf8_unicode_ci NOT NULL,
  `mp_price` int(110) NOT NULL,
  `mp_date` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `mp_depreciate` binary(1) NOT NULL DEFAULT '0',
  `mp_bpid` int(110) NOT NULL,
  PRIMARY KEY (`mp_id`),
  KEY `mp_bpid` (`mp_bpid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--
-- Creation: Dec 30, 2013 at 09:55 AM
-- Last update: Dec 30, 2013 at 09:55 AM
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `pageid` int(110) NOT NULL AUTO_INCREMENT,
  `parentid` int(110) NOT NULL,
  `pageurl` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pagetitle` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `pageorder` int(110) NOT NULL,
  `page_content` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`pageid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=71 ;

-- --------------------------------------------------------

--
-- Table structure for table `page_sections`
--
-- Creation: Dec 30, 2013 at 09:55 AM
-- Last update: Dec 30, 2013 at 09:55 AM
--

DROP TABLE IF EXISTS `page_sections`;
CREATE TABLE IF NOT EXISTS `page_sections` (
  `section_id` int(110) NOT NULL AUTO_INCREMENT,
  `s_pageid` int(110) NOT NULL,
  `section_order` int(110) NOT NULL,
  `section_desc` text COLLATE utf8_unicode_ci,
  `section_title` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `section_content` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`section_id`),
  KEY `s_pageid` (`s_pageid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `sales_12_month_forecast`
--
-- Creation: Dec 30, 2013 at 09:55 AM
--

DROP TABLE IF EXISTS `sales_12_month_forecast`;
CREATE TABLE IF NOT EXISTS `sales_12_month_forecast` (
  `smf_id` int(110) NOT NULL AUTO_INCREMENT,
  `month_01` int(110) NOT NULL DEFAULT '0',
  `month_02` int(110) NOT NULL DEFAULT '0',
  `month_03` int(110) NOT NULL DEFAULT '0',
  `month_04` int(110) NOT NULL DEFAULT '0',
  `month_05` int(110) NOT NULL DEFAULT '0',
  `month_06` int(110) NOT NULL DEFAULT '0',
  `month_07` int(110) NOT NULL DEFAULT '0',
  `month_08` int(110) NOT NULL DEFAULT '0',
  `month_09` int(110) NOT NULL DEFAULT '0',
  `month_10` int(110) NOT NULL DEFAULT '0',
  `month_11` int(110) NOT NULL DEFAULT '0',
  `month_12` int(110) NOT NULL DEFAULT '0',
  `price` decimal(11,2) NOT NULL DEFAULT '0.00',
  `cost` decimal(11,2) NOT NULL DEFAULT '0.00',
  `sales_forecast_id` int(110) NOT NULL,
  PRIMARY KEY (`smf_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

-- --------------------------------------------------------

--
-- Table structure for table `sales_financial_forecast`
--
-- Creation: Dec 30, 2013 at 09:55 AM
--

DROP TABLE IF EXISTS `sales_financial_forecast`;
CREATE TABLE IF NOT EXISTS `sales_financial_forecast` (
  `sff_id` int(110) NOT NULL AUTO_INCREMENT,
  `financial_year` varchar(255) NOT NULL,
  `total_per_yr` int(110) NOT NULL,
  `sales_forecast_id` int(110) NOT NULL,
  PRIMARY KEY (`sff_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=61 ;

-- --------------------------------------------------------

--
-- Table structure for table `sales_forecast`
--
-- Creation: Dec 30, 2013 at 09:55 AM
--

DROP TABLE IF EXISTS `sales_forecast`;
CREATE TABLE IF NOT EXISTS `sales_forecast` (
  `sf_id` int(110) NOT NULL AUTO_INCREMENT,
  `sales_forecast_name` varchar(225) NOT NULL,
  `sales_forecast_bp_id` int(110) NOT NULL,
  PRIMARY KEY (`sf_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `loan_investment_12m_payment`
--
ALTER TABLE `loan_investment_12m_payment`
  ADD CONSTRAINT `loan_investment_12m_payment_ibfk_1` FOREIGN KEY (`limp_loan_investment_id`) REFERENCES `loan_investment` (`li_id`);

--
-- Constraints for table `loan_investment_12m_received`
--
ALTER TABLE `loan_investment_12m_received`
  ADD CONSTRAINT `loan_investment_12m_received_ibfk_1` FOREIGN KEY (`limr_loan_investment_id`) REFERENCES `loan_investment` (`li_id`);

--
-- Constraints for table `loan_investment_payment_f_yrs`
--
ALTER TABLE `loan_investment_payment_f_yrs`
  ADD CONSTRAINT `loan_investment_payment_f_yrs_ibfk_1` FOREIGN KEY (`lip_loan_investment_id`) REFERENCES `loan_investment` (`li_id`);

--
-- Constraints for table `loan_investment_received_f_yrs`
--
ALTER TABLE `loan_investment_received_f_yrs`
  ADD CONSTRAINT `loan_investment_received_f_yrs_ibfk_1` FOREIGN KEY (`lir_loan_investment_id`) REFERENCES `loan_investment` (`li_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
