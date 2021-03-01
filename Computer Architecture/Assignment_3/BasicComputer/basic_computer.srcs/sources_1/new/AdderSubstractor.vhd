library IEEE;
use IEEE.STD_LOGIC_1164.ALL;

-- Rafa

entity AdderSubstractor is
    Port ( a : in std_logic_vector (11 downto 0);
           b : in std_logic_vector (11 downto 0);
           e : in STD_LOGIC; -- Selector entre suma / resta
           c : out STD_LOGIC; -- Carry out. Solo sera usado en la ALU
           s : out std_logic_vector (11 downto 0));
end AdderSubstractor;

architecture Behavioral of AdderSubstractor is

-- Seran usados 12 FA, 1 por cada bit
component FA
    Port ( a : in STD_LOGIC;
           b : in STD_LOGIC;
           ci : in STD_LOGIC;
           s : out STD_LOGIC;
           c : out STD_LOGIC);
    end component;
    

signal b2 : std_logic_vector (11 downto 0) := (others => '0');
signal c0_1 : STD_LOGIC;
signal c1_2 : STD_LOGIC;
signal c2_3 : STD_LOGIC;
signal c3_4 : STD_LOGIC;
signal c4_5 : STD_LOGIC;
signal c5_6 : STD_LOGIC;
signal c6_7 : STD_LOGIC;
signal c7_8 : STD_LOGIC;
signal c8_9 : STD_LOGIC;
signal c9_10 : STD_LOGIC;
signal c10_11 : STD_LOGIC;

begin

with e select -- Mux para los bits de suma
    b2 <=   b when '0',
            not b when '1',
            (others => '0') when others;


inst_FA0: FA port map(
        a     => a(0),
        b     => b2(0),
        ci    => e, -- El carry in será 1 si se eligio restar!
        s     => s(0),
        c     => c0_1
    );
      
 inst_FA1: FA port map(
        a     => a(1),
        b     => b2(1),
        ci    => c0_1,
        s     => s(1),
        c     => c1_2
    );

 inst_FA2: FA port map(
        a     => a(2),
        b     => b2(2),
        ci    => c1_2,
        s     => s(2),
        c     => c2_3
    );

 inst_FA3: FA port map(
        a     => a(3),
        b     => b2(3),
        ci    => c2_3,
        s     => s(3),
        c     => c3_4
    );
    
 inst_FA4: FA port map(
        a     => a(4),
        b     => b2(4),
        ci    => c3_4,
        s     => s(4),
        c     => c4_5
    );
    
 inst_FA5: FA port map(
        a     => a(5),
        b     => b2(5),
        ci    => c4_5,
        s     => s(5),
        c     => c5_6
    );

 inst_FA6: FA port map(
        a     => a(6),
        b     => b2(6),
        ci    => c5_6,
        s     => s(6),
        c     => c6_7
    );

 inst_FA7: FA port map(
        a     => a(7),
        b     => b2(7),
        ci    => c6_7,
        s     => s(7),
        c     => c7_8
    );

 inst_FA8: FA port map(
        a     => a(8),
        b     => b2(8),
        ci    => c7_8,
        s     => s(8),
        c     => c8_9
    );
    
 inst_FA9: FA port map(
        a     => a(9),
        b     => b2(9),
        ci    => c8_9,
        s     => s(9),
        c     => c9_10
    );
    
 inst_FA10: FA port map(
        a     => a(10),
        b     => b2(10),
        ci    => c9_10,
        s     => s(10),
        c     => c10_11
    );
    
 inst_FA11: FA port map(
        a     => a(11),
        b     => b2(11),
        ci    => c10_11,
        s     => s(11),
        c     => c -- Se conecta al carry out del AdderSubstractor
    );
    
end Behavioral;
