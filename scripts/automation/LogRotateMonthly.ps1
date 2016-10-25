
Function Rotate-Logs {
    <#    
 
    Public Domain License
 
    .SYNOPSIS
    Clean up and rotate files monthly
 
    .DESCRIPTION
    This script rotates files and keeps them in directory \monthly
 
    New files are expected to be written to $LogDir and Rotate-Logs moves them into subdirectories
 
    .EXAMPLE
    Rotate-Logs -LogDir "c:\MyLogDirectory"
 
    .LINKS
    http://www.getsurreal.com/powershell/powershell-file-rotation
    #>
 
	Param (
		[Parameter(Mandatory=$True,ValueFromPipeline=$false)]
		[string]$LogDir, # Directory log files are written to
		[Parameter(ValueFromPipeline=$false)]
		[int]$DayOfMonth = 1, # The day of the month to store for monthly files (Max = 28 since varying last day of month not currently handled)
		[Parameter(ValueFromPipeline=$false)]
		[int]$RotationMonthly = 5 # The number of monthly files to keep
	)
 
	Process {
		if (-not $LogDir) {
			Write-Host "Error:  -LogDir not set"
			Exit
        }
 
        $date = Get-Date
 
        $verify_log_dir = Test-Path $LogDir
        if ($verify_log_dir) {
            $verify_monthly_dir = Test-Path "$LogDir\monthly"
 
            # If the monthly directory does not exist try to create it
            if (!$verify_monthly_dir) {
                $md_monthly = md -Name "monthly" -Path $LogDir
                if (!$md_monthly){
                    Write-Host "Error setting up log directories. Check Permissions."
                    exit
                }
            }
        }
        else {
            Write-Host "Error:  Log directory $LogDir does not exist."
            exit
        }
 
        $logs_root = Get-ChildItem $LogDir | where {$_.Attributes -ne "Directory"}
 
        if ($logs_root) {
            foreach ($file in $logs_root) {
                $file_date = get-date $file.LastWriteTime                
                if ($file_date -ge $date.AddDays(-$RotationMonthly*30) -and [int]$file_date.Day -eq $DayOfMonth) {
                    #Write-Host "Monthly $($file.Name) - $($file_date) $([int]$file_date.DayOfWeek)"
                    Copy-Item "$LogDir\$file" "$LogDir\monthly"
                }
                Remove-Item "$LogDir\$file"
            }
 
            $logs_monthly = Get-ChildItem "$LogDir\monthly" | where {$_.Attributes -ne "Directory"}
 
            if ($logs_monthly) {
                foreach ($file in $logs_monthly) {
                    $file_date = get-date $file.LastWriteTime
                    if ($file_date -le $date.AddDays(-$RotationMonthly*30)) {
                        #Write-Host "$file.Name"
                        Remove-Item "$LogDir\monthly\$file"
                    }
                }
            }
        }
    }
}

