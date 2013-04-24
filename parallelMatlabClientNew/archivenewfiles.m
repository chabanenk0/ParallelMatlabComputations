function archivenewfiles(filesexcept,filename);
% ‘ункци€ отправки файлов, созданных во врем€ рассчета. Ѕуду запаковывать
% только файлы, дата которых больше, чем самый новый после распаковани€ 
global path_to_rar;
if (isempty(path_to_rar))
    path_to_rar='C:\Program Files\WinRAR\';
end
global path_to_wget;
if (isempty(path_to_wget))
    path_to_wget='E:\softSince20090830\wget\wget-1.10.2b\';
end
global path_to_curl;
if (isempty(path_to_curl))
    path_to_curl='E:\SoftSince20110824\curl-7.22.0-devel-mingw32\curl-7.22.0-devel-mingw32\bin\';
end

curfiles=dir;
maxdate='01-€нв-0000 00:00:00';
for i=1:length(filesexcept)
    if (strcmp(filesexcept(i).name,'.')==1)||(strcmp(filesexcept(i).name,'..')==1)
        continue;
    end
    
    if (compare_dates(filesexcept(i).date,maxdate)>0)
        maxdate=filesexcept(i).date;
    end
end

addfiles = [];
for i=1:length(curfiles)
    if (strcmp(curfiles(i).name,'.')==1)||(strcmp(curfiles(i).name,'..')==1)
        continue;
    end
    if (compare_dates(curfiles(i).date,maxdate)>0)
        addfiles=[addfiles ' ' curfiles(i).name];
    end
end

if (nargin<2)
    filename='computed.rar';
end
system(['"' path_to_rar 'rar" a ' filename ' ' addfiles ]);
if (0)
if (0)
    fp=fopen('ftpcommands.txt','w');
    fprintf(fp,'type binary\ncd shared\nput %s\nquit',filename);
    fclose(fp);
    system('ftp -A -s:ftpcommands.txt 127.0.0.1');
else
    
    %system(['"' path_to_wget ' wget " --proxy=on  -ehttp_proxy=http://192.168.0.10:3128 --post-file=' file ' ' url ]);
    %system(['"' path_to_curl 'curl " -x http://192.168.0.10:3128 -a -T ' filename ' ' url ]);
    system(['' path_to_curl 'curl -T ' filename ' ' url ]);
    %system(['' path_to_curl 'curl -x http://192.168.0.10:3128 -T ' filename ' ' url ]);
    %E:\SoftSince20110824\curl-7.22.0-devel-mingw32\curl-7.22.0-devel-mingw32\bin\curl -v -T 1620_done.rar ftp://prognoz.sys-admin.dp.ua/pub/
    % проблема была со слешем в конце. 
end
end




function vector=get_date_numbers(date);
% функци€ перевода текста даты в числовой вид (вектор мес€ца, дн€, года,
% час, минуты
%'22-окт-2011 21:27:41'
vector=sscanf(date,'%d-%3s-%d %d:%d:%d');
month=sprintf('%s',vector(2:4));
monthnum=0;
switch(month)
    case '€нв' 
        monthnum=1;
    case 'фев' 
        monthnum=2;
    case 'мар' 
        monthnum=3;
    case 'апр' 
        monthnum=4;
    case 'май' 
        monthnum=5;
    case 'июн' 
        monthnum=6;
    case 'июл' 
        monthnum=7;
    case 'авг' 
        monthnum=8;
    case 'сен' 
        monthnum=9;
    case 'окт' 
        monthnum=10;
     case 'но€' 
        monthnum=11;
    case 'дек' 
        monthnum=12;
end

vector=[vector(1),monthnum,vector(5),vector(6),vector(7),vector(8)];


function c=compare_dates(date1,date2);
% 1- перва€ дата больше, -1 - втора€ дата больше
vector1=get_date_numbers(date1);
vector2=get_date_numbers(date2);
if (vector1(3)>vector2(3))
    c=1;
else
    if (vector1(3)<vector2(3))
        c=-1;
    else
        if (vector1(2)>vector2(2))
            c=1;
        else
            if (vector1(2)<vector2(2))
                c=-1;
            else
                if(vector1(1)>vector2(1))
                    c=1;
                else
                    if(vector1(1)<vector2(1))
                        c=-1;
                    else
                        if (vector1(4)>vector2(4))
                            c=1;
                        else
                            if (vector1(4)<vector2(4))
                                c=-1;
                            else
                                if (vector1(5)>vector2(5))
                                    c=1;
                                else
                                    if (vector1(5)<vector2(5))
                                        c=-1;
                                    else
                                        if (vector1(6)>vector2(6))
                                            c=1;
                                        else
                                            if (vector1(6)<vector2(6))
                                                c=-1;
                                            else
                                                c=0
                                            end
                                        end
                                    end
                                end
                            end
                        end
                    end
                end
            end
        end
    end
end


                                                